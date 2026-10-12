
<?php

session_start();

require '../../banco/config.php';

// ======================================================
// VARIÁVEIS DO PAINEL
// ======================================================

$totalFuncionarios = 0;
$totalPedidos = 0;
$pedidosAguardando = 0;
$faturamentoPago = 0;

$funcionarios = [];
$pedidos = [];
$produtos = [];
$statusDisponiveis = [];

$erroBanco = '';

$filtroStatus = trim($_GET['status'] ?? '');
$busca = trim($_GET['busca'] ?? '');


// ======================================================
// FUNÇÕES AUXILIARES
// ======================================================

function escapar($valor)
{
    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        'UTF-8'
    );
}

function moeda($valor)
{
    return 'R$ ' . number_format(
        (float) $valor,
        2,
        ',',
        '.'
    );
}

function classeStatus($status)
{
    $status = mb_strtolower(trim((string) $status));

    if (
        str_contains($status, 'aguardando') ||
        str_contains($status, 'pendente')
    ) {
        return 'status-pendente';
    }

    if (
        in_array($status, [
            'pago',
            'aprovado',
            'concluido',
            'concluído',
            'finalizado',
            'succeeded',
            'complete'
        ], true)
    ) {
        return 'status-pago';
    }

    if (str_contains($status, 'cancel')) {
        return 'status-cancelado';
    }

    return 'status-outro';
}

function classeEstoque($quantidade)
{
    $quantidade = (int) $quantidade;

    if ($quantidade >= 15) {
        return 'estoque-verde';
    }

    if ($quantidade >= 6) {
        return 'estoque-amarelo';
    }

    return 'estoque-vermelho';
}


// ======================================================
// CONSULTAS AO BANCO DE DADOS
// ======================================================

try {

    // 1. Quantidade de funcionários cadastrados

    $sql = $pdo->query("
        SELECT COUNT(*)
        FROM funcionario
    ");

    $totalFuncionarios = (int) $sql->fetchColumn();


    // 2. Quantidade de pedidos e faturamento pago

    $sql = $pdo->query("
        SELECT
            COUNT(*) AS total,

            SUM(
                CASE
                    WHEN LOWER(TRIM(status_pedido))
                         LIKE '%aguardando%'
                      OR LOWER(TRIM(status_pedido))
                         LIKE '%pendente%'
                    THEN 1
                    ELSE 0
                END
            ) AS aguardando,

            SUM(
                CASE
                    WHEN LOWER(TRIM(status_pedido)) IN (
                        'pago',
                        'aprovado',
                        'concluido',
                        'concluído',
                        'finalizado',
                        'succeeded',
                        'complete'
                    )
                    THEN total_pedido
                    ELSE 0
                END
            ) AS faturamento

        FROM pedidos
    ");

    $dados = $sql->fetch(PDO::FETCH_ASSOC);

    $totalPedidos = (int) ($dados['total'] ?? 0);

    $pedidosAguardando = (int) ($dados['aguardando'] ?? 0);

    $faturamentoPago = (float) ($dados['faturamento'] ?? 0);


    // 3. Produtos e estoque

    $sql = $pdo->query("
        SELECT
            pk_id_produtos,
            nome_produto,
            estoque
        FROM produtos
        ORDER BY nome_produto ASC
    ");

    $produtos = $sql->fetchAll(PDO::FETCH_ASSOC);


    // 4. Status existentes dos pedidos

    $sql = $pdo->query("
        SELECT DISTINCT status_pedido
        FROM pedidos
        WHERE status_pedido IS NOT NULL
          AND status_pedido <> ''
        ORDER BY status_pedido
    ");

    $statusDisponiveis = $sql->fetchAll(PDO::FETCH_COLUMN);


    // 5. Listagem de pedidos
    //
    // ATENÇÃO:
    // Esta consulta considera as tabelas pessoa e itens_pedido.
    // Os nomes das chaves precisam corresponder ao seu banco.

    $sqlPedidos = "
        SELECT
            p.pk_id_pedidos,
            p.dt_pedido,
            p.total_pedido,
            p.status_pedido,

            COALESCE(c.nome, 'Cliente não identificado')
                AS nome_cliente,

            COALESCE(pg.nome_pagamento, 'Não informado')
                AS forma_pagamento,

            COALESCE(
                GROUP_CONCAT(
                    DISTINCT CONCAT(
                        pr.nome_produto,
                        ' (',
                        ip.quantidade,
                        ' un.)'
                    )
                    ORDER BY pr.nome_produto
                    SEPARATOR ', '
                ),
                'Itens não informados'
            ) AS itens

        FROM pedidos p

        LEFT JOIN pessoa pe
            ON pe.id = p.id_pessoa

        LEFT JOIN cadastro c
            ON c.id = pe.id_usuario

        LEFT JOIN pagamento pg
            ON pg.pk_id_pagamento = p.fk_id_pagamento

        LEFT JOIN itens_pedido ip
            ON ip.fk_id_pedidos = p.pk_id_pedidos

        LEFT JOIN produtos pr
            ON pr.pk_id_produtos = ip.fk_id_produtos
    ";

    $condicoes = [];
    $parametros = [];


    // Filtro por status

    if ($filtroStatus !== '') {
        $condicoes[] = 'p.status_pedido = :status';
        $parametros[':status'] = $filtroStatus;
    }


    // Busca por número do pedido ou nome do cliente

    if ($busca !== '') {

        $condicoes[] = "(
            CAST(p.pk_id_pedidos AS CHAR) LIKE :pedido
            OR c.nome LIKE :cliente
        )";

        $parametros[':pedido'] = '%' . $busca . '%';
        $parametros[':cliente'] = '%' . $busca . '%';
    }


    if (!empty($condicoes)) {
        $sqlPedidos .= ' WHERE ' . implode(' AND ', $condicoes);
    }


    $sqlPedidos .= "
        GROUP BY
            p.pk_id_pedidos,
            p.dt_pedido,
            p.total_pedido,
            p.status_pedido,
            c.nome,
            pg.nome_pagamento

        ORDER BY p.dt_pedido DESC
    ";


    $consulta = $pdo->prepare($sqlPedidos);
    $consulta->execute($parametros);

    $pedidos = $consulta->fetchAll(PDO::FETCH_ASSOC);


} catch (PDOException $e) {

    error_log(
        'Erro no painel de vendas: ' . $e->getMessage()
    );

    $erroBanco = 'Não foi possível carregar os dados. Verifique a estrutura das tabelas do banco de dados.';
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Analisar Vendas - Artesanatos Eloi</title>

    <link
        rel="stylesheet"
        href="../css/style-pagina-adm.css"
    >

    <link rel="stylesheet" href="../css/style-analisar-vendas.css">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    >
</head>

<body>

    <!-- ==========================================
         CABEÇALHO ADMINISTRATIVO
    =========================================== -->

    <div class="header">

        <!-- MENU LATERAL MOBILE -->

        <div class="menu-mobile" id="menuMobile">

            <div class="menu-mobile-conteudo">

                <button
                    class="fechar-menu"
                    onclick="fecharMenu()"
                    type="button"
                >
                    ✕
                </button>

                <a href="../../pagina-inicial-adm.php">
                    Início
                </a>

                <a href="../../editar-produtos/edit-products.php">
                    Editar Produtos
                </a>

                <div class="menu-mobile-titulo">
                    Cadastro
                </div>

                <a href="../../cadastrar-produtos/cadastro-produtos.php">
                    Produtos
                </a>

                <a href="../../categorias/categorias.php">
                    Categorias
                </a>

                <a href="../../pagamento/forma-pagamento.php">
                    Forma de Pagamento
                </a>

                <a href="../../funcionarios/cadastro-funcionario.php">
                    Funcionários
                </a>

                <a href="analisar-vendas.php">
                    Analisar Vendas
                </a>

            </div>

        </div>


        <!-- BARRA SUPERIOR -->

        <div class="top-bar">

            <a href="../pagina-inicial-adm.php">

                <img
                    src="../../img/artes-removebg-preview.png"
                    class="img-logo"
                    alt="Artesanatos Eloi"
                >

            </a>


            <form method="GET" class="search">

                <input
                    type="text"
                    name="busca"
                    id="pesquisar"
                    placeholder="Buscar pedido ou cliente..."
                    value="<?= escapar($busca) ?>"
                >

                <button type="submit" class="search-btn">

                    <img
                        src="../../img/search.svg"
                        alt="Pesquisar"
                    >

                </button>

            </form>


            <div class="actions">

                <div class="dropdown-button">

                    <button
                        type="button"
                        class="button-dropdown"
                    >

                        <img
                            src="../../img/person.svg"
                            alt="Conta administrativa"
                        >

                    </button>


                    <div class="conteudo-dropdown">

                        <a href="../../login-adm.php">
                            Login
                        </a>

                        <a href="../pagina-inicial-adm.php">
                            Página do Admin
                            <?= escapar($_SESSION['user_nome'] ?? '') ?>
                        </a>

                        <?php if (isset($_SESSION['user_id'])): ?>

                            <a href="../../banco/logout-admin.php">
                                Sair
                            </a>

                        <?php endif; ?>

                    </div>

                </div>


                <button type="button">

                    <a href="#" aria-label="Notificações">

                        <img
                            src="../../img/bell.svg"
                            alt="Notificações"
                        >

                    </a>

                </button>

            </div>

        </div>


        <!-- MENU DESKTOP -->

        <div class="bottom-bar">

            <a
                href="../pagina-inicial-adm.php"
                class="link-animado"
            >
                Início
            </a>

            <a
                href="../../editar-produtos/edit-products.php"
                class="link-animado"
            >
                Editar Produtos
            </a>


            <div class="dropdown">

                <a href="#" class="link-animado">
                    Cadastro
                </a>

                <div class="dropdown-menu">

                    <a href="../../cadastrar-produtos/cadastro-produtos.php">
                        Produtos
                    </a>

                    <a href="../../categorias/categorias.php">
                        Categorias
                    </a>

                    <a href="../../pagamento/forma-pagamento.php">
                        Forma de Pagamento
                    </a>

                    <a href="../../funcionarios/cadastro-funcionario.php">
                        Funcionários
                    </a>

                </div>

            </div>


            <a
                href="analisar-vendas.php"
                class="link-animado"
            >
                Analisar Vendas
            </a>

        </div>

    </div>


    <!-- ==========================================
         CONTEÚDO DO PAINEL
    =========================================== -->

    <main class="conteudo-vendas">

        <h1 class="titulo-vendas">
            Analisar Vendas
        </h1>

        <p class="subtitulo-vendas">
            Acompanhe os pedidos, o faturamento e o estoque dos produtos.
        </p>


        <?php if ($erroBanco !== ''): ?>

            <div class="mensagem-erro-vendas">

                <i class="fa-solid fa-circle-exclamation"></i>

                <?= escapar($erroBanco) ?>

            </div>

        <?php endif; ?>


        <!-- INDICADORES -->

        <section class="indicadores-vendas">

            <article class="card-indicador-vendas">

                <div class="indicador-cabecalho">

                    <span class="indicador-titulo">
                        Funcionários cadastrados
                    </span>

                    <div class="indicador-icone icone-azul">
                        <i class="fa-solid fa-users"></i>
                    </div>

                </div>

                <div class="indicador-numero">
                    <?= number_format($totalFuncionarios, 0, ',', '.') ?>
                </div>

                <div class="indicador-descricao">
                    Total registrado no sistema
                </div>

            </article>


            <article class="card-indicador-vendas">

                <div class="indicador-cabecalho">

                    <span class="indicador-titulo">
                        Pedidos realizados
                    </span>

                    <div class="indicador-icone icone-laranja">
                        <i class="fa-solid fa-bag-shopping"></i>
                    </div>

                </div>

                <div class="indicador-numero">
                    <?= number_format($totalPedidos, 0, ',', '.') ?>
                </div>

                <div class="indicador-descricao">
                    Inclui pedidos aguardando pagamento
                </div>

            </article>


            <article class="card-indicador-vendas">

                <div class="indicador-cabecalho">

                    <span class="indicador-titulo">
                        Aguardando pagamento
                    </span>

                    <div class="indicador-icone icone-amarelo">
                        <i class="fa-solid fa-clock"></i>
                    </div>

                </div>

                <div class="indicador-numero">
                    <?= number_format($pedidosAguardando, 0, ',', '.') ?>
                </div>

                <div class="indicador-descricao">
                    Pedidos ainda pendentes
                </div>

            </article>


            <article class="card-indicador-vendas">

                <div class="indicador-cabecalho">

                    <span class="indicador-titulo">
                        Faturamento pago
                    </span>

                    <div class="indicador-icone icone-verde">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>

                </div>

                <div class="indicador-numero">
                    <?= escapar(moeda($faturamentoPago)) ?>
                </div>

                <div class="indicador-descricao">
                    Soma dos pedidos reconhecidos como pagos
                </div>

            </article>

        </section>


        <!-- LISTA DE PEDIDOS -->

        <h2 class="titulo-secao-vendas">
            <i class="fa-solid fa-receipt"></i>
            Pedidos e vendas
        </h2>


        <section class="painel-vendas">

            <div class="cabecalho-painel-vendas">

                <div>

                    <h3>Pedidos realizados</h3>

                    <p>
                        Consulte os pedidos e suas situações de pagamento.
                    </p>

                </div>

                <span class="indicador-descricao">
                    <?= count($pedidos) ?> resultado(s)
                </span>

            </div>


            <form method="GET" class="filtros-vendas">

                <input
                    type="search"
                    name="busca"
                    class="campo-vendas busca-vendas"
                    placeholder="Buscar pedido ou cliente..."
                    value="<?= escapar($busca) ?>"
                >


                <select
                    name="status"
                    class="campo-vendas"
                >

                    <option value="">
                        Todos os status
                    </option>

                    <?php foreach ($statusDisponiveis as $status): ?>

                        <option
                            value="<?= escapar($status) ?>"
                            <?= $filtroStatus === $status ? 'selected' : '' ?>
                        >
                            <?= escapar($status) ?>
                        </option>

                    <?php endforeach; ?>

                </select>


                <button
                    type="submit"
                    class="botao-filtrar-vendas"
                >

                    <i class="fa-solid fa-magnifying-glass"></i>
                    Filtrar

                </button>


                <a
                    href="analisar-vendas.php"
                    class="link-limpar-vendas"
                >
                    Limpar filtros
                </a>

            </form>


            <div class="tabela-responsiva-vendas">

                <table class="tabela-vendas">

                    <thead>

                        <tr>
                            <th>Pedido</th>
                            <th>Data</th>
                            <th>Cliente</th>
                            <th>Produtos</th>
                            <th>Pagamento</th>
                            <th>Valor</th>
                            <th>Status</th>
                        </tr>

                    </thead>


                    <tbody>

                        <?php if (empty($pedidos)): ?>

                            <tr>

                                <td
                                    colspan="7"
                                    class="mensagem-vazia-vendas"
                                >

                                    <i class="fa-solid fa-inbox fa-2x"></i>

                                    <p class="mt-3 mb-0">
                                        Nenhum pedido encontrado.
                                    </p>

                                </td>

                            </tr>

                        <?php else: ?>

                            <?php foreach ($pedidos as $pedido): ?>

                                <tr>

                                    <td>
                                        <strong>
                                            #<?= (int) $pedido['pk_id_pedidos'] ?>
                                        </strong>
                                    </td>


                                    <td>

                                        <?php if (!empty($pedido['dt_pedido'])): ?>

                                            <?= escapar(
                                                date(
                                                    'd/m/Y H:i',
                                                    strtotime($pedido['dt_pedido'])
                                                )
                                            ) ?>

                                        <?php else: ?>

                                            Não informada

                                        <?php endif; ?>

                                    </td>


                                    <td class="nome-cliente-venda">
                                        <?= escapar($pedido['nome_cliente']) ?>
                                    </td>


                                    <td class="itens-venda">
                                        <?= escapar($pedido['itens']) ?>
                                    </td>


                                    <td>
                                        <?= escapar($pedido['forma_pagamento']) ?>
                                    </td>


                                    <td class="valor-venda">
                                        <?= escapar(moeda($pedido['total_pedido'])) ?>
                                    </td>


                                    <td>

                                        <span class="status-venda <?= escapar(classeStatus($pedido['status_pedido'])) ?>">

                                            <?= escapar(
                                                $pedido['status_pedido']
                                                ?: 'Não informado'
                                            ) ?>

                                        </span>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </section>


        <!-- RESUMO DO ESTOQUE -->

        <h2 class="titulo-secao-vendas">
            <i class="fa-solid fa-boxes-stacked"></i>
            Resumo do estoque
        </h2>


        <section class="painel-vendas">

            <div class="cabecalho-painel-vendas">

                <div>

                    <h3>Produtos cadastrados</h3>

                    <p>
                        Acompanhe a quantidade disponível de cada produto.
                    </p>

                </div>


                <div class="legenda-estoque">

                    <div class="item-legenda-estoque">
                        <span class="bolinha-estoque bolinha-verde"></span>
                        15 ou mais
                    </div>

                    <div class="item-legenda-estoque">
                        <span class="bolinha-estoque bolinha-amarela"></span>
                        6 a 14
                    </div>

                    <div class="item-legenda-estoque">
                        <span class="bolinha-estoque bolinha-vermelha"></span>
                        0 a 5
                    </div>

                </div>

            </div>


            <div class="tabela-responsiva-vendas">

                <table class="tabela-vendas">

                    <thead>

                        <tr>
                            <th>ID</th>
                            <th>Nome do produto</th>
                            <th>Estoque disponível</th>
                            <th>Situação</th>
                        </tr>

                    </thead>


                    <tbody>

                        <?php if (empty($produtos)): ?>

                            <tr>

                                <td
                                    colspan="4"
                                    class="mensagem-vazia-vendas"
                                >
                                    Nenhum produto cadastrado.
                                </td>

                            </tr>

                        <?php else: ?>

                            <?php foreach ($produtos as $produto): ?>

                                <?php
                                $quantidade = (int) $produto['estoque'];
                                $classe = classeEstoque($quantidade);
                                ?>

                                <tr>

                                    <td>
                                        <?= (int) $produto['pk_id_produtos'] ?>
                                    </td>


                                    <td class="nome-produto-venda">
                                        <?= escapar($produto['nome_produto']) ?>
                                    </td>


                                    <td>

                                        <span class="quantidade-estoque <?= escapar($classe) ?>">

                                            <?= $quantidade ?>

                                            <?= $quantidade === 1
                                                ? 'unidade'
                                                : 'unidades' ?>

                                        </span>

                                    </td>


                                    <td>

                                        <?php if ($quantidade >= 15): ?>

                                            <span class="status-venda status-pago">
                                                <i class="fa-solid fa-circle-check"></i>
                                                Estoque adequado
                                            </span>

                                        <?php elseif ($quantidade >= 6): ?>

                                            <span class="status-venda status-pendente">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                Atenção ao estoque
                                            </span>

                                        <?php else: ?>

                                            <span class="status-venda status-cancelado">
                                                <i class="fa-solid fa-circle-exclamation"></i>
                                                Estoque baixo
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </section>

    </main>


    <footer class="text-center text-secondary py-4">
        Artesanatos Eloi &copy; <?= date('Y') ?>.
        Painel administrativo.
    </footer>


    <!-- SCRIPTS DO PROJETO -->

    <script src="../js/script-adm.js"></script>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
    </script>

</body>

</html>