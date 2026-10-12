<?php

//PARTE DO CÓDIGO PHP RESPONSÁVEL POR MOSTRAR TODOS OS DADOS ARMAZENADOS NAS TABELAS DE FUNCIONÁRIOS, CATEGORIAS DE PRODUTOS E DAS FORMAS DE PAGAMENTO
require '../banco/config.php';

session_start();

//Fazendo o Select da tabela da categoria de Produtos
$listaCategorias = [];

$sql = $pdo->query("SELECT * FROM categorias");
if ($sql->rowCount() > 0) {
    $listaCategorias = $sql->fetchAll(PDO::FETCH_ASSOC);
}

//Fazendo o Select da tabela das Formas de Pagamento
$listaPagamento = [];

$sql = $pdo->query("SELECT * FROM pagamento");
if ($sql->rowCount() > 0) {
    $listaPagamento = $sql->fetchAll(PDO::FETCH_ASSOC);
}

//Fazendo o Select da tabela da produtos com as primeiras informações de cada produto
$listaProdutos = [];

$sql = $pdo->query("SELECT * FROM produtos");
if ($sql->rowCount() > 0) {
    $listaProdutos = $sql->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Funcionários - Artesanatos Eloi</title>
    <link rel="stylesheet" href="../admin/css/style-pagina-adm.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
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

                <a href="#">Analisar Vendas</a>

            </div>
        </div>

        <!-- PARTE DE CIMA DO TOP BAR -->
        <div class="top-bar">
            <a href="javascript:void(0)" onclick="abrirMenu()">
                <img src="../img/artes-removebg-preview.png"
                    class="img-logo"
                    alt="Logo">
            </a>

            <!-- BARRA DE PESQUISA -->
            <form method="POST" class="search">
                <input
                    type="text"
                    name="search"
                    id="pesquisar"
                    placeholder="Buscar Produtos..."
                    value="<?= isset($_POST['search']) ? htmlspecialchars($_POST['search']) : '' ?>">

                <button type="submit" class="search-btn">
                    <img src="../img/search.svg" alt="Pesquisar">
                </button>
            </form>

            <!-- IMAGENS/BOTÕES DAS NOTIFICAÇÕES E DAS CONFIGURAÇÕES -->
            <div class="actions">

                <div class="dropdown-button">

                    <button class="button-dropdown">
                        <img src="../img/person.svg" alt="Login">
                    </button>

                    <div class="conteudo-dropdown">
                        <a href="../funcionarios/pagina-inicial-func.php">
                            Página do Funcionário
                            <?= htmlspecialchars($_SESSION['user_nome'] ?? '') ?>
                        </a>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="../banco/logout-usuario.php">Sair</a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- BOTÃO DE NOTIFICAÇÃO -->
                <button>
                    <a href=""><img src="../img/bell.svg" alt="Notificações"></a>
                </button>

            </div>
        </div>

        <!-- PARTE DE BAIXO DO TOP BAR COM OS LINKS DE NAVEGAÇÃO -->
        <div class="bottom-bar">
            <a href="../funcionarios/pagina-inicial-func.php" class="link-animado">Início</a>
            <a href="../editar-produtos/edit-products.php" class="link-animado">Editar Produtos</a>
            <div class="dropdown">
                <a href="#" class="link-animado">Cadastro</a>

                <div class="dropdown-menu">
                    <a href="../cadastrar-produtos/cadastro-produtos.php">Produtos</a>
                    <a href="../categorias/categorias.php">Categorias</a>
                    <a href="../pagamento/forma-pagamento.php">Forma de Pagamento</a>
                </div>
            </div>
            <a href="../funcionarios/analisar-vendas-funcionarios.php" class="link-animado">Analisar Vendas</a>
        </div>
    </div>


    <!-- TELA INICIAL DO ADMINISTRADOR, ONDE SERÁ POSSÍVEL VER OS FUNCIONÁRIOS CADASTRADOS E AS NOTIFICAÇÕES -->
    <div class="inicio">
        <div class="cabecalho-dashboard">
            <h1 class="title">Painel do Funcionário</h1>
            <p class="subtitulo">
                Gerencie formas de pagamento, categorias e produtos
            </p>
        </div>

        <div class="menu-rapido">
            <div class="card-menu">
                <button class="btn-cadastro"><a href="../cadastrar-produtos/cadastro-produtos.php">Cadastro Produtos</a></button>
            </div>

            <div class="card-menu">
                <button class="btn-cadastro"><a href="../categorias/categorias.php">Cadastro Categorias</a></button>
            </div>

            <div class="card-menu">
                <button class="btn-cadastro"><a href="../editar-produtos/edit-products.php">Analisar Produtos</a></button>
            </div>
        </div>

        <div class="conteudo-admin">

            <div class="lado-esquerdo">

                <!-- TABELA COM ALGUMAS INFORMAÇÕES DOS PRODUTOS CADASTRADOS -->
                <h3 class="title-tabela">Produtos Cadastrados</h3>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Preço</th>
                                <th>Estoque</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($listaProdutos as $produtos): ?>
                                <tr>
                                    <td><?= $produtos['pk_id_produtos'] ?></td>
                                    <td><?= $produtos['nome_produto'] ?></td>
                                    <td> R$<?= $produtos['preco_produto'] ?></td>
                                    <td><?= $produtos['estoque'] ?></td>
                                    <td>
                                        <button
                                            type="button"
                                            class="btn btn-secondary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#JanelaModal<?= $produtos['pk_id_produtos'] ?>">
                                            Visualizar
                                        </button>

                                        <div class="modal fade" id="JanelaModal<?= $produtos['pk_id_produtos'] ?>" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Visualizar Categoria</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <p><b>ID do Produto:</b> <?= $produtos['pk_id_produtos'] ?></p>
                                                        <p><b>Nome do Produto:</b> <?= $produtos['nome_produto'] ?></p>
                                                        <p><b>Descrição do Produto:</b> <?= $produtos['descricao'] ?></p>
                                                        <p><b>Preço do Produto:</b> R$ <?= $produtos['preco_produto'] ?></p>
                                                        <p><b>Estoque do Produto:</b> <?= $produtos['estoque'] ?></p>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <a href="../banco/edit-produtos.php?id=<?= $produtos['pk_id_produtos']; ?>" class="btn btn-primary">
                                            Editar
                                        </a>

                                        <button class="btn btn-danger">Excluir</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <br>

                <!-- TABELA COM ALGUMAS INFORMAÇÕES DE CATEGORIAS CADASTRADAS -->
                <h3 class="title-tabela">Categoria dos Produtos</h3>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Categoria</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($listaCategorias as $categorias): ?>
                                <tr>
                                    <td><?= $categorias['pk_id_categorias'] ?></td>
                                    <td><?= $categorias['nome_categoria'] ?></td>
                                    <td>
                                        <button
                                            type="button"
                                            class="btn btn-secondary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#JanelaModal<?= $categorias['pk_id_categorias'] ?>">
                                            Visualizar
                                        </button>

                                        <div class="modal fade" id="JanelaModal<?= $categorias['pk_id_categorias'] ?>" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Visualizar Categoria</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <p><b>ID da Categoria:</b> <?= $categorias['pk_id_categorias'] ?></p>
                                                        <p><b>Nome da Categoria:</b> <?= $categorias['nome_categoria'] ?></p>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <a href="../banco/edit-categorias.php?id=<?= $categorias['pk_id_categorias']; ?>" class="btn btn-primary">
                                            Editar
                                        </a>
                                        <a href="../banco/excluir.php?tabela=categorias&campo=pk_id_categorias&id=<?= $categorias['pk_id_categorias']; ?>"
                                            class="btn btn-danger"
                                            onclick="return confirm('Deseja excluir esta categoria?')">
                                            Excluir
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <br>

                <!-- TABELA COM ALGUMAS INFORMAÇÕES DAS FORMAS DE PAGAMENTO CADASTRADAS -->
                <h3 class="title-tabela">Formas de Pagamento</h3>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Forma de Pagamento</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($listaPagamento as $pagamento): ?>
                                <tr>
                                    <td><?= $pagamento['pk_id_pagamento'] ?></td>
                                    <td><?= $pagamento['nome_pagamento'] ?></td>
                                    <td>
                                        <button
                                            type="button"
                                            class="btn btn-secondary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#JanelaModal<?= $pagamento['pk_id_pagamento'] ?>">
                                            Visualizar
                                        </button>

                                        <div class="modal fade" id="JanelaModal<?= $pagamento['pk_id_pagamento'] ?>" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Visualizar Pagamento</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <p><b>ID do Pagamento:</b> <?= $pagamento['pk_id_pagamento'] ?></p>
                                                        <p><b>Nome do Pagamento:</b> <?= $pagamento['nome_pagamento'] ?></p>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <a href="../banco/edit-pagamento.php?id=<?= $pagamento['pk_id_pagamento']; ?>" class="btn btn-primary">
                                            Editar
                                        </a>
                                        <a href="../banco/excluir.php?tabela=pagamento&campo=pk_id_pagamento&id=<?= $pagamento['pk_id_pagamento']; ?>"
                                            class="btn btn-danger"
                                            onclick="return confirm('Deseja excluir esta forma de pagamento?')">
                                            Excluir
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

        <!--<div class="panel-pendences">

                <div class="header-notificacao">
                    <h3>Notificações</h3>
                    <span class="badge-notificacao">3</span>
                </div>

                <div class="lista-notificacoes">

                    <div class="notificacao urgente">
                        <h4>Novo funcionário adicionado</h4>
                        <p>Um novo funcionário com o nome João Silva foi cadastrado.</p>
                        <span>Há 5 minutos</span>
                    </div>

                    <div class="notificacao alerta">
                        <h4>Estoque baixo</h4>
                        <p>O produto caminhão de madeira possui apenas 4 unidades.</p>
                        <span>Há 20 minutos</span>
                    </div>

                    <div class="notificacao sucesso">
                        <h4>Produto Adicionado</h4>
                        <p>O brinquedo - Piano de Madeira foi adicionado ao estoque.</p>
                        <span>Há 1 hora</span>
                    </div>

                </div>

            </div>-->
    </div>

    <!-- PARTE DO JAVASCRIPT -->
    <script src="../js/script-adm.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>