
<?php

// ======================================================
// INICIANDO A SESSÃO E CONECTANDO AO BANCO
// ======================================================

session_start();

require_once __DIR__ . '/../banco/config.php';

// Verifica se o funcionário está logado.
if (!isset($_SESSION['user_id'])) {
    header('Location: ../admin/login-adm.php');
    exit;
}

// Verifica se o funcionário está ativo.
try {
    $sqlFuncionario = $pdo->prepare("
        SELECT idfuncionario, nomefuncionario
        FROM funcionario
        WHERE idfuncionario = :id
          AND statusfuncionario = 1
        LIMIT 1
    ");

    $sqlFuncionario->execute([
        ':id' => $_SESSION['user_id']
    ]);

    $funcionario = $sqlFuncionario->fetch(PDO::FETCH_ASSOC);

    if (!$funcionario) {
        http_response_code(403);
        exit('Acesso permitido somente a funcionários ativos.');
    }

    $_SESSION['user_nome'] = $funcionario['nomefuncionario'];

} catch (PDOException $e) {
    error_log($e->getMessage());
    exit('Não foi possível verificar o acesso ao sistema.');
}

$nomeFuncionario = $funcionario['nomefuncionario'];

// ======================================================
// TOKEN DE SEGURANÇA
// ======================================================

if (empty($_SESSION['csrf_funcionario'])) {
    $_SESSION['csrf_funcionario'] = bin2hex(random_bytes(32));
}

$mensagem = '';
$tipoMensagem = '';

// ======================================================
// ATUALIZAÇÃO DAS ETAPAS OPERACIONAIS
// ======================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_pedido'])) {

    $token = $_POST['csrf_token'] ?? '';
    $idPedido = filter_input(INPUT_POST, 'id_pedido', FILTER_VALIDATE_INT);
    $novaEtapa = $_POST['nova_etapa'] ?? '';

    $etapasPermitidas = [
        'Aguardando preparação',
        'Em preparação',
        'Pronto para envio',
        'Enviado',
        'Concluído'
    ];

    if (
        !is_string($token) ||
        !hash_equals($_SESSION['csrf_funcionario'], $token)
    ) {
        $mensagem = 'Solicitação inválida. Atualize a página e tente novamente.';
        $tipoMensagem = 'erro';

    } elseif (
        !$idPedido ||
        !in_array($novaEtapa, $etapasPermitidas, true)
    ) {
        $mensagem = 'Os dados enviados são inválidos.';
        $tipoMensagem = 'erro';

    } else {
        try {
            $sqlPedido = $pdo->prepare("
                SELECT pk_id_pedidos, status_pedido, status_operacional
                FROM pedidos
                WHERE pk_id_pedidos = :id
                LIMIT 1
            ");

            $sqlPedido->execute([':id' => $idPedido]);
            $pedidoAtual = $sqlPedido->fetch(PDO::FETCH_ASSOC);

            if (!$pedidoAtual) {
                $mensagem = 'Pedido não encontrado.';
                $tipoMensagem = 'erro';

            } else {
                // IMPORTANTE:
                // Ajuste os valores abaixo aos status reais do pagamento.
                $statusPagamento = mb_strtolower(
                    trim((string) $pedidoAtual['status_pedido']),
                    'UTF-8'
                );

                $pagamentoConfirmado = in_array(
                    $statusPagamento,
                    ['pago', 'aprovado', 'aprovada', 'paid', 'succeeded'],
                    true
                );

                $etapaAtual = $pedidoAtual['status_operacional']
                    ?: 'Aguardando pagamento';

                $fluxo = [
                    'Aguardando pagamento' => 0,
                    'Aguardando preparação' => 1,
                    'Em preparação' => 2,
                    'Pronto para envio' => 3,
                    'Enviado' => 4,
                    'Concluído' => 5
                ];

                if (!$pagamentoConfirmado) {
                    $mensagem = 'O pagamento ainda não foi confirmado. Nenhuma etapa foi alterada.';
                    $tipoMensagem = 'erro';

                } elseif (
                    !isset($fluxo[$etapaAtual]) ||
                    $fluxo[$etapaAtual] >= 4
                ) {
                    $mensagem = 'Este pedido já foi enviado ou concluído, ou possui uma etapa inválida.';
                    $tipoMensagem = 'erro';

                } elseif (
                    !isset($fluxo[$novaEtapa]) ||
                    $fluxo[$novaEtapa] !== $fluxo[$etapaAtual] + 1
                ) {
                    $mensagem = 'Só é permitido avançar para a próxima etapa.';
                    $tipoMensagem = 'erro';

                } else {
                    // Atualiza apenas a etapa operacional.
                    // O status do pagamento não é alterado.
                    $atualizar = $pdo->prepare("
                        UPDATE pedidos
                        SET status_operacional = :etapa
                        WHERE pk_id_pedidos = :id
                    ");

                    $atualizar->execute([
                        ':etapa' => $novaEtapa,
                        ':id' => $idPedido
                    ]);

                    $mensagem = 'Etapa operacional atualizada com sucesso!';
                    $tipoMensagem = 'sucesso';
                }
            }

        } catch (PDOException $e) {
            error_log($e->getMessage());
            $mensagem = 'Não foi possível atualizar o pedido.';
            $tipoMensagem = 'erro';
        }
    }
}

// ======================================================
// FILTROS DOS PEDIDOS
// ======================================================

$busca = trim($_GET['search'] ?? '');
$filtroEtapa = trim($_GET['etapa'] ?? '');

$etapasFiltro = [
    'Aguardando pagamento',
    'Aguardando preparação',
    'Em preparação',
    'Pronto para envio',
    'Enviado',
    'Concluído'
];

if (!in_array($filtroEtapa, $etapasFiltro, true)) {
    $filtroEtapa = '';
}

// ======================================================
// VARIÁVEIS DA PÁGINA
// ======================================================

$totalPedidos = 0;
$aguardandoPagamento = 0;
$pedidosEmAndamento = 0;
$totalProdutos = 0;

$pedidos = [];
$produtosEstoque = [];
$erroConsulta = false;

// ======================================================
// CONSULTA DOS INDICADORES, PEDIDOS E ESTOQUE
// ======================================================

try {
    // Total de pedidos.
    $totalPedidos = (int) $pdo
        ->query("SELECT COUNT(*) FROM pedidos")
        ->fetchColumn();

    // Pedidos aguardando pagamento.
    $aguardandoPagamento = (int) $pdo->query("
        SELECT COUNT(*)
        FROM pedidos
        WHERE LOWER(status_pedido) IN (
            'pendente',
            'aguardando pagamento',
            'pending'
        )
    ")->fetchColumn();

    // Pedidos em andamento.
    $pedidosEmAndamento = (int) $pdo->query("
        SELECT COUNT(*)
        FROM pedidos
        WHERE status_operacional IN (
            'Aguardando preparação',
            'Em preparação',
            'Pronto para envio'
        )
    ")->fetchColumn();

    // Produtos ativos.
    $totalProdutos = (int) $pdo->query("
        SELECT COUNT(*)
        FROM produtos
        WHERE status_produto = 1
    ")->fetchColumn();

    // Lista de pedidos.
    $sql = "
        SELECT
            p.pk_id_pedidos,
            p.dt_pedido,
            p.total_pedido,
            p.status_pedido,
            p.status_operacional,
            c.nome AS nome_cliente,
            pg.nome_pagamento,
            COALESCE(
                (
                    SELECT SUM(ip.quantidade)
                    FROM itens_pedido ip
                    WHERE ip.fk_id_pedidos = p.pk_id_pedidos
                ),
                0
            ) AS quantidade_itens
        FROM pedidos p
        LEFT JOIN pessoa pe
            ON pe.id = p.id_pessoa
        LEFT JOIN cadastro c
            ON c.id = pe.id_usuario
        LEFT JOIN pagamento pg
            ON pg.pk_id_pagamento = p.fk_id_pagamento
        WHERE 1 = 1
    ";

    $parametros = [];

    if ($busca !== '') {
        $sql .= "
            AND (
                CAST(p.pk_id_pedidos AS CHAR) LIKE :busca
                OR c.nome LIKE :busca
            )
        ";

        $parametros[':busca'] = '%' . $busca . '%';
    }

    if ($filtroEtapa !== '') {
        $sql .= " AND p.status_operacional = :etapa ";
        $parametros[':etapa'] = $filtroEtapa;
    }

    $sql .= " ORDER BY p.dt_pedido DESC LIMIT 100";

    $consultaPedidos = $pdo->prepare($sql);
    $consultaPedidos->execute($parametros);
    $pedidos = $consultaPedidos->fetchAll(PDO::FETCH_ASSOC);

    // Produtos com menor estoque primeiro.
    $consultaEstoque = $pdo->query("
        SELECT pk_id_produtos, nome_produto, estoque
        FROM produtos
        WHERE status_produto = 1
        ORDER BY estoque ASC, nome_produto ASC
        LIMIT 10
    ");

    $produtosEstoque = $consultaEstoque->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log($e->getMessage());
    $erroConsulta = true;

    $mensagem = 'Não foi possível carregar os dados. Confira os nomes das tabelas e colunas do banco.';
    $tipoMensagem = 'erro';
}

// ======================================================
// FUNÇÕES AUXILIARES
// ======================================================

function e($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function dinheiro($valor) {
    return 'R$ ' . number_format((float) $valor, 2, ',', '.');
}

function classeEstoque($quantidade) {
    if ($quantidade <= 5) {
        return 'estoque-baixo';
    }

    if ($quantidade <= 14) {
        return 'estoque-medio';
    }

    return 'estoque-bom';
}

function proximaEtapa($etapa) {
    $fluxo = [
        'Aguardando preparação' => 'Em preparação',
        'Em preparação' => 'Pronto para envio',
        'Pronto para envio' => 'Enviado',
        'Enviado' => 'Concluído'
    ];

    return $fluxo[$etapa] ?? null;
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Página Funcionários - Artesanatos Eloi</title>

    <!-- CSS administrativo existente -->
    <link rel="stylesheet" href="../admin/css/style-pagina-adm.css">

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous"
    >

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    >

    <style>
        /* ==========================================
           CONTEÚDO PRINCIPAL
        ========================================== */

        .conteudo-funcionario {
            width: min(1200px, 94%);
            margin: 30px auto;
            color: #2b2b2b;
        }

        .titulo-funcionario {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .subtitulo-funcionario {
            color: #666;
            margin-bottom: 25px;
        }

        .mensagem-sistema {
            padding: 14px 18px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .mensagem-sistema.sucesso {
            background: #e5f7e9;
            color: #176b32;
            border: 1px solid #b8e5c3;
        }

        .mensagem-sistema.erro {
            background: #fff0ed;
            color: #a52a1a;
            border: 1px solid #f2c1b9;
        }

        /* INDICADORES */

        .indicadores-funcionario {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 30px;
        }

        .indicador-funcionario {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 22px;
            background: #fff;
            border: 1px solid #e8e8e8;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .04);
        }

        .icone-indicador {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: #fff0df;
            color: #f07f0d;
            font-size: 21px;
        }

        .indicador-funcionario h3 {
            margin: 0 0 6px;
            color: #666;
            font-size: 14px;
            font-weight: 500;
        }

        .valor-indicador {
            font-size: 26px;
            font-weight: 700;
        }

        /* SEÇÕES */

        .secao-funcionario {
            padding: 24px;
            margin-bottom: 25px;
            background: #fff;
            border: 1px solid #e8e8e8;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .04);
        }

        .cabecalho-secao {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .cabecalho-secao h2 {
            margin: 0;
            font-size: 21px;
        }

        .cabecalho-secao p {
            margin: 6px 0 0;
            color: #777;
            font-size: 14px;
        }

        /* FILTROS */

        .filtros-pedidos {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }

        .filtros-pedidos input,
        .filtros-pedidos select {
            min-height: 42px;
            padding: 9px 12px;
            border: 1px solid #ddd;
            border-radius: 7px;
            background: #fff;
            font-size: 14px;
        }

        .filtros-pedidos input {
            flex: 1;
            min-width: 200px;
        }

        .botao-padrao {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            padding: 10px 15px;
            border: none;
            border-radius: 7px;
            background: #ff8f1f;
            color: #fff;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
        }

        .botao-padrao:hover {
            background: #e9780b;
            color: #fff;
        }

        .botao-secundario {
            background: #f1f1f1;
            color: #333;
        }

        .botao-secundario:hover {
            background: #e2e2e2;
            color: #222;
        }

        /* TABELA */

        .tabela-responsiva {
            width: 100%;
            overflow-x: auto;
        }

        .tabela-funcionario {
            width: 100%;
            min-width: 850px;
            border-collapse: collapse;
        }

        .tabela-funcionario th,
        .tabela-funcionario td {
            padding: 14px 12px;
            text-align: left;
            vertical-align: middle;
            border-bottom: 1px solid #eee;
        }

        .tabela-funcionario th {
            background: #fff5e9;
            font-size: 13px;
            white-space: nowrap;
        }

        .tabela-funcionario td {
            font-size: 14px;
        }

        .tabela-funcionario tbody tr:hover {
            background: #fffaf5;
        }

        .status-etiqueta {
            display: inline-block;
            padding: 6px 9px;
            border-radius: 20px;
            background: #f1f1f1;
            color: #444;
            font-size: 12px;
            font-weight: 600;
        }

        .status-pagamento {
            background: #fff0d9;
            color: #88500a;
        }

        .status-operacional {
            background: #e8f4ff;
            color: #155b8b;
        }

        .acao-pedido {
            min-width: 155px;
        }

        .acao-pedido form {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .acao-pedido button {
            font-size: 12px;
        }

        .texto-secundario {
            display: block;
            margin-top: 5px;
            color: #777;
            font-size: 12px;
        }

        /* ESTOQUE */

        .lista-estoque {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .item-estoque {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 8px;
        }

        .nome-produto-estoque {
            font-weight: 600;
        }

        .quantidade-estoque {
            white-space: nowrap;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .estoque-baixo {
            background: #ffe1df;
            color: #b42318;
        }

        .estoque-medio {
            background: #fff0c2;
            color: #865d00;
        }

        .estoque-bom {
            background: #dcf5e4;
            color: #176b32;
        }

        .legenda-estoque {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 18px;
            color: #555;
            font-size: 12px;
        }

        .bolinha {
            width: 10px;
            height: 10px;
            display: inline-block;
            border-radius: 50%;
        }

        .bolinha-vermelha { background: #d92d20; }
        .bolinha-amarela { background: #e9ad12; }
        .bolinha-verde { background: #239447; }

        .vazio {
            padding: 25px;
            text-align: center;
            color: #777;
        }

        @media (max-width: 1000px) {
            .indicadores-funcionario {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .lista-estoque {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .conteudo-funcionario {
                width: 94%;
                margin: 20px auto;
            }

            .titulo-funcionario {
                font-size: 23px;
            }

            .indicadores-funcionario {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .indicador-funcionario {
                padding: 17px;
            }

            .secao-funcionario {
                padding: 16px;
            }

            .cabecalho-secao {
                align-items: flex-start;
                flex-direction: column;
            }

            .filtros-pedidos {
                flex-direction: column;
            }

            .filtros-pedidos input,
            .filtros-pedidos select,
            .filtros-pedidos button {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="header">

    <!-- MENU LATERAL MOBILE -->
    <div class="menu-mobile" id="menuMobile">
        <div class="menu-mobile-conteudo">

            <button class="fechar-menu" onclick="fecharMenu()">
                ✕
            </button>

            <a href="../funcionarios/pagina-inicial-func.php">Início</a>
            <a href="../editar-produtos/edit-products.php">Editar Produtos</a>

            <div class="menu-mobile-titulo">
                Cadastro
            </div>

            <a href="../cadastrar-produtos/cadastro-produtos.php">
                Produtos
            </a>

            <a href="../categorias/categorias.php">
                Categorias
            </a>

            <a href="../pagamento/forma-pagamento.php">
                Forma de Pagamento
            </a>

            <a href="../funcionarios/analisar-vendas-funcionarios.php">
                Analisar Vendas
            </a>

        </div>
    </div>

    <!-- PARTE SUPERIOR DO CABEÇALHO -->
    <div class="top-bar">

        <a href="javascript:void(0)" onclick="abrirMenu()">
            <img
                src="../img/artes-removebg-preview.png"
                class="img-logo"
                alt="Logo Artesanatos Eloi"
            >
        </a>

        <!-- PESQUISA -->
        <form method="GET" class="search">
            <input
                type="text"
                name="search"
                id="pesquisar"
                placeholder="Buscar pedidos..."
                value="<?= e($busca); ?>"
            >

            <button type="submit" class="search-btn">
                <img src="../img/search.svg" alt="Pesquisar">
            </button>
        </form>

        <!-- AÇÕES DO CABEÇALHO -->
        <div class="actions">

            <div class="dropdown-button">
                <button type="button" class="button-dropdown">
                    <img src="../img/person.svg" alt="Conta">
                </button>

                <div class="conteudo-dropdown">
                    <a href="../funcionarios/pagina-inicial-func.php">
                        Página do Funcionário
                        <?= e($nomeFuncionario); ?>
                    </a>

                    <a href="../banco/logout-admin.php">
                        Sair
                    </a>
                </div>
            </div>

            <button type="button" aria-label="Notificações">
                <a href="#">
                    <img src="../img/bell.svg" alt="Notificações">
                </a>
            </button>

        </div>
    </div>

    <!-- NAVEGAÇÃO DESKTOP -->
    <div class="bottom-bar">

        <a
            href="../funcionarios/pagina-inicial-func.php"
            class="link-animado"
        >
            Início
        </a>

        <a
            href="../editar-produtos/edit-products.php"
            class="link-animado"
        >
            Editar Produtos
        </a>

        <div class="dropdown">
            <a href="#" class="link-animado">Cadastro</a>

            <div class="dropdown-menu">
                <a href="../cadastrar-produtos/cadastro-produtos.php">
                    Produtos
                </a>

                <a href="../categorias/categorias.php">
                    Categorias
                </a>

                <a href="../pagamento/forma-pagamento.php">
                    Forma de Pagamento
                </a>
            </div>
        </div>

        <a
            href="../funcionarios/analisar-vendas-funcionarios.php"
            class="link-animado"
        >
            Analisar Vendas
        </a>

    </div>
</div>

<!-- ==================================================
     CONTEÚDO DA PÁGINA INICIAL DO FUNCIONÁRIO
================================================== -->

<main class="conteudo-funcionario">

    <h1 class="titulo-funcionario">
        Olá, <?= e($nomeFuncionario); ?>!
    </h1>

    <p class="subtitulo-funcionario">
        Acompanhe os pedidos, organize as etapas de preparação
        e consulte o estoque da Artesanatos Eloi.
    </p>

    <?php if ($mensagem !== ''): ?>
        <div class="mensagem-sistema <?= e($tipoMensagem); ?>">
            <?= e($mensagem); ?>
        </div>
    <?php endif; ?>

    <?php if ($erroConsulta): ?>
        <section class="secao-funcionario">
            <p>
                Não foi possível carregar os dados. Verifique a estrutura
                das tabelas do banco de dados.
            </p>
        </section>
    <?php else: ?>

    <!-- INDICADORES -->
    <section class="indicadores-funcionario">

        <article class="indicador-funcionario">
            <div class="icone-indicador">
                <i class="fa-solid fa-box"></i>
            </div>
            <div>
                <h3>Total de pedidos</h3>
                <div class="valor-indicador">
                    <?= $totalPedidos; ?>
                </div>
            </div>
        </article>

        <article class="indicador-funcionario">
            <div class="icone-indicador">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <h3>Aguardando pagamento</h3>
                <div class="valor-indicador">
                    <?= $aguardandoPagamento; ?>
                </div>
            </div>
        </article>

        <article class="indicador-funcionario">
            <div class="icone-indicador">
                <i class="fa-solid fa-gears"></i>
            </div>
            <div>
                <h3>Em andamento</h3>
                <div class="valor-indicador">
                    <?= $pedidosEmAndamento; ?>
                </div>
            </div>
        </article>

        <article class="indicador-funcionario">
            <div class="icone-indicador">
                <i class="fa-solid fa-cubes-stacked"></i>
            </div>
            <div>
                <h3>Produtos ativos</h3>
                <div class="valor-indicador">
                    <?= $totalProdutos; ?>
                </div>
            </div>
        </article>

    </section>

    <!-- GERENCIAMENTO DOS PEDIDOS -->
    <section class="secao-funcionario">

        <div class="cabecalho-secao">
            <div>
                <h2>Gerenciamento de pedidos</h2>
                <p>
                    Consulte os pedidos e avance suas etapas operacionais.
                </p>
            </div>
        </div>

        <form method="GET" class="filtros-pedidos">

            <input
                type="text"
                name="search"
                placeholder="Pesquisar por número ou cliente"
                value="<?= e($busca); ?>"
            >

            <select name="etapa">
                <option value="">Todas as etapas</option>

                <?php foreach ($etapasFiltro as $etapa): ?>
                    <option
                        value="<?= e($etapa); ?>"
                        <?= $filtroEtapa === $etapa ? 'selected' : ''; ?>
                    >
                        <?= e($etapa); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="botao-padrao">
                <i class="fa-solid fa-magnifying-glass"></i>
                Filtrar
            </button>

            <a
                href="pagina-inicial-func.php"
                class="botao-padrao botao-secundario"
            >
                Limpar
            </a>

        </form>

        <div class="tabela-responsiva">

            <table class="tabela-funcionario">

                <thead>
                    <tr>
                        <th>Pedido</th>
                        <th>Data</th>
                        <th>Cliente</th>
                        <th>Itens</th>
                        <th>Valor</th>
                        <th>Pagamento</th>
                        <th>Etapa operacional</th>
                        <th>Ação</th>
                    </tr>
                </thead>

                <tbody>

                <?php if (empty($pedidos)): ?>

                    <tr>
                        <td colspan="8" class="vazio">
                            Nenhum pedido encontrado.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($pedidos as $pedido): ?>

                        <?php
                        $etapaAtual = $pedido['status_operacional']
                            ?: 'Aguardando pagamento';

                        $proxima = proximaEtapa($etapaAtual);

                        $pagamentoTexto = $pedido['status_pedido']
                            ?: 'Não informado';

                        $statusPagamento = mb_strtolower(
                            trim($pagamentoTexto),
                            'UTF-8'
                        );

                        $pagamentoConfirmado = in_array(
                            $statusPagamento,
                            ['pago', 'aprovado', 'aprovada', 'paid', 'succeeded'],
                            true
                        );
                        ?>

                        <tr>
                            <td>
                                #<?= (int) $pedido['pk_id_pedidos']; ?>
                            </td>

                            <td>
                                <?php if (!empty($pedido['dt_pedido'])): ?>
                                    <?= e(date(
                                        'd/m/Y H:i',
                                        strtotime($pedido['dt_pedido'])
                                    )); ?>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>

                            <td>
                                <?= e(
                                    $pedido['nome_cliente']
                                    ?: 'Cliente não identificado'
                                ); ?>
                            </td>

                            <td>
                                <?= (int) $pedido['quantidade_itens']; ?>
                            </td>

                            <td>
                                <?= dinheiro($pedido['total_pedido']); ?>
                            </td>

                            <td>
                                <span class="status-etiqueta status-pagamento">
                                    <?= e($pagamentoTexto); ?>
                                </span>

                                <?php if (!empty($pedido['nome_pagamento'])): ?>
                                    <span class="texto-secundario">
                                        <?= e($pedido['nome_pagamento']); ?>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <span class="status-etiqueta status-operacional">
                                    <?= e($etapaAtual); ?>
                                </span>
                            </td>

                            <td class="acao-pedido">

                                <?php if (!$pagamentoConfirmado): ?>

                                    <span class="texto-secundario">
                                        Aguardando confirmação do pagamento.
                                    </span>

                                <?php elseif ($proxima !== null): ?>

                                    <form method="POST">

                                        <input
                                            type="hidden"
                                            name="csrf_token"
                                            value="<?= e($_SESSION['csrf_funcionario']); ?>"
                                        >

                                        <input
                                            type="hidden"
                                            name="id_pedido"
                                            value="<?= (int) $pedido['pk_id_pedidos']; ?>"
                                        >

                                        <input
                                            type="hidden"
                                            name="nova_etapa"
                                            value="<?= e($proxima); ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="botao-padrao"
                                            onclick="return confirm('Deseja avançar este pedido para <?= e($proxima); ?>?');"
                                        >
                                            <i class="fa-solid fa-arrow-right"></i>
                                            <?= e($proxima); ?>
                                        </button>

                                    </form>

                                <?php else: ?>

                                    <span class="texto-secundario">
                                        Etapa finalizada.
                                    </span>

                                <?php endif; ?>

                            </td>
                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

                </tbody>
            </table>

        </div>

        <p class="texto-secundario">
            A página apresenta até 100 pedidos. O funcionário não confirma
            pagamentos manualmente.
        </p>

    </section>

    <!-- CONSULTA DO ESTOQUE -->
    <section class="secao-funcionario">

        <div class="cabecalho-secao">
            <div>
                <h2>Consulta de estoque</h2>
                <p>
                    Produtos ativos ordenados pela menor quantidade disponível.
                </p>
            </div>
        </div>

        <?php if (empty($produtosEstoque)): ?>

            <div class="vazio">
                Nenhum produto ativo encontrado.
            </div>

        <?php else: ?>

            <div class="lista-estoque">

                <?php foreach ($produtosEstoque as $produto): ?>

                    <?php $quantidade = (int) $produto['estoque']; ?>

                    <article class="item-estoque">

                        <div>
                            <div class="nome-produto-estoque">
                                <?= e($produto['nome_produto']); ?>
                            </div>

                            <span class="texto-secundario">
                                Código:
                                <?= (int) $produto['pk_id_produtos']; ?>
                            </span>
                        </div>

                        <span class="quantidade-estoque <?= classeEstoque($quantidade); ?>">
                            <?= $quantidade; ?>
                            <?= $quantidade === 1 ? 'unidade' : 'unidades'; ?>
                        </span>

                    </article>

                <?php endforeach; ?>

            </div>

            <div class="legenda-estoque">
                <span>
                    <i class="bolinha bolinha-vermelha"></i>
                    Estoque baixo: até 5
                </span>

                <span>
                    <i class="bolinha bolinha-amarela"></i>
                    Estoque intermediário: 6 a 14
                </span>

                <span>
                    <i class="bolinha bolinha-verde"></i>
                    Estoque adequado: 15 ou mais
                </span>
            </div>

        <?php endif; ?>

    </section>

    <?php endif; ?>

</main>

<!-- Bootstrap -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"
></script>

<!-- JavaScript administrativo existente -->
<script src="../admin/js/script-adm.js"></script>

</body>
</html>