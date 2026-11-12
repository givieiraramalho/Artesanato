<?php

session_start();

require_once '../banco/config.php';
require_once '../stripe/init.php';

// ==========================================
// VERIFICANDO SE O USUÁRIO ESTÁ LOGADO
// ==========================================
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}


// ==========================================
// VERIFICANDO SE O CARRINHO EXISTE
// ==========================================

if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    header("Location: finalizar-compra.php");
    exit;
}


// ==========================================
// CHAVE SECRETA DA STRIPE
// ==========================================

$stripe_secret_key = "SUA_CHAVE_AQUI";
\Stripe\Stripe::setApiKey($stripe_secret_key);


// ==========================================
// CRIANDO OS PRODUTOS DO CHECKOUT
// ==========================================

$line_items = [];
$produtosValidados = []; // Guarda os itens já calculados para salvar no banco depois
$totalCompra = 0;

foreach ($_SESSION['carrinho'] as $id_produto => $quantidade) {

    // 1. Incluímos a coluna 'desconto' no SELECT e validamos se está ativo
    $sql = $pdo->prepare("
        SELECT 
            pk_id_produtos,
            nome_produto,
            preco_produto,
            desconto,
            estoque
        FROM produtos
        WHERE pk_id_produtos = ?
          AND status_produto = 1
    ");

    $sql->execute([(int) $id_produto]);
    $produto = $sql->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        continue;
    }

    // ==========================================
    // VERIFICANDO O ESTOQUE
    // ==========================================
    $quantidade = (int) $quantidade;
    if ($quantidade > (int) $produto['estoque']) {
        die("O produto " . htmlspecialchars($produto['nome_produto']) . " não possui estoque suficiente.");
    }

    // ==========================================
    // CALCULANDO PREÇO COM DESCONTO E SUBTOTAL
    // ==========================================
    $precoOriginal = (float) $produto['preco_produto'];
    $porcentagemDesconto = (float) ($produto['desconto'] ?? 0);

    if ($porcentagemDesconto > 0) {
        $precoUnitario = round($precoOriginal - ($precoOriginal * ($porcentagemDesconto / 100)), 2);
    } else {
        $precoUnitario = round($precoOriginal, 2);
    }

    $subtotal = round($precoUnitario * $quantidade, 2);
    $totalCompra += $subtotal;

    // Guarda para usar na gravação do banco sem precisar consultar tudo de novo
    $produtosValidados[] = [
        'id_produto'     => $produto['pk_id_produtos'],
        'quantidade'     => $quantidade,
        'preco_unitario' => $precoUnitario,
        'subtotal'       => $subtotal
    ];

    // ==========================================
    // ADICIONANDO O PRODUTO COM DESCONTO À STRIPE
    // ==========================================
    $line_items[] = [
        'quantity' => $quantidade,
        'price_data' => [
            'currency' => 'brl',
            // Usa o $precoUnitario já com desconto aplicado
            'unit_amount' => (int) round($precoUnitario * 100),
            'product_data' => [
                'name' => $produto['nome_produto']
            ]
        ]
    ];
}

// ==========================================
// SE NÃO HOUVER PRODUTOS
// ==========================================
if (empty($line_items)) {
    header("Location: ../carrinho-compras/carrinho-compras.php");
    exit;
}

// ==========================================
// BUSCANDO O ENDEREÇO DO USUÁRIO
// ==========================================
$sqlEndereco = $pdo->prepare("
    SELECT *
    FROM endereco
    WHERE id_usuario = ?
");

$sqlEndereco->execute([$_SESSION['user_id']]);
$endereco = $sqlEndereco->fetch(PDO::FETCH_ASSOC);

// ==========================================
// CALCULANDO O FRETE
// ==========================================
$frete = 0;
$estado = strtoupper(trim($endereco['estado'] ?? ''));

switch ($estado) {
    case 'SP':
    case 'RJ':
    case 'MG':
    case 'ES':
        $frete = 15;
        break;

    case 'PR':
    case 'SC':
    case 'RS':
        $frete = 20;
        break;

    case 'GO':
    case 'MT':
    case 'MS':
    case 'DF':
        $frete = 25;
        break;

    case 'AL':
    case 'BA':
    case 'CE':
    case 'MA':
    case 'PB':
    case 'PE':
    case 'PI':
    case 'RN':
    case 'SE':
        $frete = 30;
        break;

    case 'AC':
    case 'AM':
    case 'AP':
    case 'PA':
    case 'RO':
    case 'RR':
    case 'TO':
        $frete = 35;
        break;

    default:
        $frete = 40;
        break;
}

// ==========================================
// FRETE GRÁTIS (considera o total já com descontos)
// ==========================================
if ($totalCompra >= 100) {
    $frete = 0;
}

// ==========================================
// ADICIONANDO O FRETE À STRIPE
// ==========================================
if ($frete > 0) {
    $line_items[] = [
        'quantity' => 1,
        'price_data' => [
            'currency' => 'brl',
            'unit_amount' => (int) round($frete * 100), // Adicionado '*' que faltava
            'product_data' => [
                'name' => 'Frete'
            ]
        ]
    ];
}

// ==========================================
// BUSCANDO OS DADOS DO CLIENTE
// ==========================================
$idUsuario = (int) $_SESSION['user_id'];

$sqlPessoa = $pdo->prepare("
    SELECT id
    FROM pessoa
    WHERE id_usuario = ?
    LIMIT 1
");

$sqlPessoa->execute([$idUsuario]);
$pessoa = $sqlPessoa->fetch(PDO::FETCH_ASSOC);

if (!$pessoa) {
    exit("Cadastre suas informações pessoais antes de comprar.");
}

if (!$endereco) {
    exit("Cadastre seu endereço antes de comprar.");
}

$sqlPagamento = $pdo->prepare("
    SELECT pk_id_pagamento
    FROM pagamento
    WHERE nome_pagamento = 'Cartão'
    LIMIT 1
");

$sqlPagamento->execute();
$pagamento = $sqlPagamento->fetch(PDO::FETCH_ASSOC);

if (!$pagamento) {
    exit("A forma de pagamento não está cadastrada.");
}

// ==========================================
// CRIANDO O PEDIDO E SALVANDO ITENS COM DESCONTO
// ==========================================
$totalFinal = round($totalCompra + $frete, 2);

try {

    $pdo->beginTransaction();

    $sqlPedido = $pdo->prepare("
        INSERT INTO pedidos (
            dt_pedido,
            total_pedido,
            status_pedido,
            fk_id_endereco,
            fk_id_pagamento,
            id_pessoa
        )
        VALUES (
            NOW(),
            ?,
            'Aguardando pagamento',
            ?,
            ?,
            ?
        )
    ");

    $sqlPedido->execute([
        $totalFinal,
        $endereco['pk_id_endereco'],
        $pagamento['pk_id_pagamento'],
        $pessoa['id']
    ]);

    $idPedido = (int) $pdo->lastInsertId();

    // ==========================================
    // SALVANDO OS PRODUTOS DO PEDIDO (COM DESCONTO)
    // ==========================================
    $sqlItem = $pdo->prepare("
        INSERT INTO itens_pedido (
            fk_id_pedidos,
            fk_id_produtos,
            quantidade,
            preco_unitario,
            subtotal
        )
        VALUES (?, ?, ?, ?, ?)
    ");

    foreach ($produtosValidados as $item) {
        $sqlItem->execute([
            $idPedido,
            $item['id_produto'],
            $item['quantidade'],
            $item['preco_unitario'],
            $item['subtotal']
        ]);
    }

    $pdo->commit();

} catch (Throwable $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log($e->getMessage());
    exit("Não foi possível registrar o pedido. Verifique os dados e tente novamente.");
}

// ==========================================
// CRIANDO A SESSÃO DO STRIPE CHECKOUT
// ==========================================

$checkout_session = \Stripe\Checkout\Session::create([
    'mode' => 'payment',

    'client_reference_id' => (string) $idUsuario,

    'metadata' => [
        'pedido_id' => (string) $idPedido
    ],

    'line_items' => $line_items,

    'success_url' => 'http://localhost/artesanatos-eloi/finalizar-pagamento/sucesso.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => 'http://localhost/artesanatos-eloi/finalizar-compra/finalizar-compra.php'
]);

// Associa a sessão Stripe ao pedido criado
$sqlSessao = $pdo->prepare("
    UPDATE pedidos
    SET stripe_session_id = ?
    WHERE pk_id_pedidos = ?
");

$sqlSessao->execute([
    $checkout_session->id,
    $idPedido
]);

// Redireciona o cliente para o Stripe
header("Location: " . $checkout_session->url);
exit;