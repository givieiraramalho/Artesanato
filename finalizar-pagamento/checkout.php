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

$totalCompra = 0;


foreach ($_SESSION['carrinho'] as $id_produto => $quantidade) {

    // Busca o produto no banco
    $sql = $pdo->prepare("
        SELECT 
            pk_id_produtos,
            nome_produto,
            preco_produto,
            estoque
        FROM produtos
        WHERE pk_id_produtos = ?
    ");

    $sql->execute([$id_produto]);

    $produto = $sql->fetch(PDO::FETCH_ASSOC);


    // Se o produto não existir, pula para o próximo
    if (!$produto) {
        continue;
    }


    // ==========================================
    // VERIFICANDO O ESTOQUE
    // ==========================================

    if ($quantidade > $produto['estoque']) {

        die("O produto " .
            htmlspecialchars($produto['nome_produto']) .
            " não possui estoque suficiente.");
    }


    // ==========================================
    // CALCULANDO SUBTOTAL
    // ==========================================

    $subtotal = $produto['preco_produto'] * $quantidade;

    $totalCompra += $subtotal;


    // ==========================================
    // ADICIONANDO O PRODUTO À STRIPE
    // ==========================================

    $line_items[] = [

        // Quantidade escolhida pelo cliente
        'quantity' => (int) $quantidade,

        'price_data' => [

            // Moeda brasileira
            'currency' => 'brl',

            // Stripe trabalha em centavos
            // Exemplo:
            // R$ 20,00 -> 2000
            'unit_amount' => (int) round(
                $produto['preco_produto'] * 100
            ),

            'product_data' => [

                // Nome real do produto
                'name' => $produto['nome_produto']
            ]
        ]
    ];
}


// ==========================================
// SE NÃO HOUVER PRODUTOS
// ==========================================

if (empty($line_items)) {

    header("Location: ../carinho-compras/carrinho-compra.php");
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

$sqlEndereco->execute([
    $_SESSION['user_id']
]);

$endereco = $sqlEndereco->fetch(PDO::FETCH_ASSOC);


// ==========================================
// CALCULANDO O FRETE
// ==========================================

$frete = 0;


// Pega o estado do endereço
$estado = strtoupper(
    trim($endereco['estado'] ?? '')
);


// Calcula o frete de acordo com o estado
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
// FRETE GRÁTIS
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

            'unit_amount' => (int) round($frete * 100),

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

// Busca a pessoa vinculada à conta
$sqlPessoa = $pdo->prepare("
    SELECT id
    FROM pessoa
    WHERE id_usuario = ?
    LIMIT 1
");

$sqlPessoa->execute([$idUsuario]);
$pessoa = $sqlPessoa->fetch(PDO::FETCH_ASSOC);

// Verifica se os dados pessoais existem
if (!$pessoa) {
    exit("Cadastre suas informações pessoais antes de comprar.");
}

// Verifica se o endereço existe
if (!$endereco) {
    exit("Cadastre seu endereço antes de comprar.");
}

// Busca a forma de pagamento cadastrada
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
// CRIANDO O PEDIDO
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

    // ESTE É O TRECHO QUE ESTAVA FALTANDO:
    // Recupera o ID do pedido recém-criado
    $idPedido = (int) $pdo->lastInsertId();

    // ==========================================
    // SALVANDO OS PRODUTOS DO PEDIDO
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

    foreach ($_SESSION['carrinho'] as $idProduto => $quantidade) {

        // Consulta novamente o preço atual do produto
        $sqlProduto = $pdo->prepare("
            SELECT pk_id_produtos, preco_produto
            FROM produtos
            WHERE pk_id_produtos = ?
              AND status_produto = 1
        ");

        $sqlProduto->execute([(int) $idProduto]);
        $produtoPedido = $sqlProduto->fetch(PDO::FETCH_ASSOC);

        if (!$produtoPedido) {
            throw new Exception("Um produto do carrinho não está disponível.");
        }

        $quantidade = (int) $quantidade;

        $precoUnitario = (float) $produtoPedido['preco_produto'];
        $subtotalItem = round($precoUnitario * $quantidade, 2);

        $sqlItem->execute([
            $idPedido,
            $produtoPedido['pk_id_produtos'],
            $quantidade,
            $precoUnitario,
            $subtotalItem
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


// ==========================================
// REDIRECIONANDO PARA A STRIPE
// ==========================================

header("Location: " . $checkout_session->url);

exit;
