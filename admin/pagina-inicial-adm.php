<?php

require '../banco/config.php';

// Fazendo o Select da tabela funcionário
$lista = [];

if (!empty($_POST['search'])) {

    $data = "%" . $_POST['search'] . "%";

    $sql = $pdo->prepare("
        SELECT *
        FROM funcionario
        WHERE idfuncionario LIKE ?
        OR nomefuncionario LIKE ?
        OR emailfuncionario LIKE ?
        ORDER BY idfuncionario ASC
    ");

    $sql->execute([$data, $data, $data]);
} else {

    $sql = $pdo->query("
        SELECT *
        FROM funcionario
        ORDER BY idfuncionario ASC
    ");
}

if ($sql->rowCount() > 0) {
    $lista = $sql->fetchAll(PDO::FETCH_ASSOC);
}

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
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Artesanatos Eloi</title>
    <link rel="stylesheet" href="style-pagina-adm.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <div class="header">

        <!-- PARTE DE CIMA DO TOP BAR -->
        <div class="top-bar">
            <a href=""><img src="../img/artes-removebg-preview.png" class="img-logo" alt="Logo"></a>

            <!-- BARRA DE PESQUISA -->
            <form method="POST" class="search">
                <input
                    type="text"
                    name="search"
                    id="pesquisar"
                    placeholder="Buscar funcionários..."
                    value="<?= isset($_POST['search']) ? htmlspecialchars($_POST['search']) : '' ?>">

                <button type="submit" class="search-btn">
                    <img src="../img/search.svg" alt="Pesquisar">
                </button>
            </form>

            <!-- IMAGENS/BOTÕES DAS NOTIFICAÇÕES E DAS CONFIGURAÇÕES -->
            <div class="actions">
                <button><a href=""><img src="../img/bell.svg" alt="Notificações"></a></button>
                <button><img src="../img/gear.svg" alt="Configurações"></button>
            </div>
        </div>

        <!-- PARTE DE BAIXO DO TOP BAR COM OS LINKS DE NAVEGAÇÃO -->
        <div class="bottom-bar">
            <a href="../admin/pagina-inicial-adm.php" class="link-animado">Início</a>
            <a href="../editar-produtos/edit-products.php" class="link-animado">Editar Produtos</a>
            <div class="dropdown">
                <a href="#" class="link-animado">Cadastro</a>

                <div class="dropdown-menu">
                    <a href="../cadastrar-produtos/cadastro-produtos.php">Produtos</a>
                    <a href="../categorias/categorias.php">Categorias</a>
                    <a href="../pagamento/forma-pagamento.php">Forma de Pagamento</a>
                    <a href="../funcionarios/cadastro-funcionario.php">Funcionários</a>
                </div>
            </div>
            <a href="" class="link-animado">Analisar Vendas</a>
        </div>
    </div>
    </div>

    <!-- TELA INICIAL DO ADMINISTRADOR, ONDE SERÁ POSSÍVEL VER OS FUNCIONÁRIOS CADASTRADOS E AS NOTIFICAÇÕES -->
    <div class="inicio">
        <div class="cabecalho-dashboard">
            <h1 class="title">Painel Administrativo</h1>
            <p class="subtitulo">
                Gerencie funcionários, categorias e produtos
            </p>
        </div>

        <div class="menu-rapido">
            <div class="card-menu">
                <button class="btn-cadastro"><a href="../cadastrar-produtos/cadastro-produtos.php">Cadastro Produtos</a></button>
            </div>

            <div class="card-menu">
                <button class="btn-cadastro"><a href="../funcionarios/cadastro-funcionario.php">Cadastro Funcionários</a></button>
            </div>

            <div class="card-menu">
                <button class="btn-cadastro"><a href="../editar-produtos/edit-products.php">Analisar Produtos</a></button>
            </div>
        </div>

        <div class="conteudo-admin">

            <div class="lado-esquerdo">

                <!-- TABELA COM ALGUMAS INFORMAÇÕES DE FUNCIONÁRIOS CADASTRADOS -->
                <div class="card-admin">
                    <h3 class="title-tabela">Funcionários Cadastrados</h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Cargo</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lista as $usuario): ?>
                                <tr>
                                    <td><?= $usuario['idfuncionario'] ?></td>
                                    <td><?= $usuario['nomefuncionario'] ?></td>
                                    <td><?= $usuario['emailfuncionario'] ?></td>
                                    <td><?= $usuario['cargofuncionario'] ?></td>
                                    <td>
                                        <button class="btn btn-success">Visualizar</button>
                                        <a href="../banco/edit-funcionarios.php?id=<?= $usuario['idfuncionario']; ?>" class="btn btn-primary">
                                            Editar
                                        </a>
                                        <button class="btn btn-danger">Excluir</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <br>

                    <!-- TABELA COM ALGUMAS INFORMAÇÕES DE CATEGORIAS CADASTRADAS -->
                    <h3 class="title-tabela">Categoria dos Produtos</h3>
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
                                        <button class="btn btn-success">Visualizar</button>
                                        <a href="../banco/edit-categorias.php?id=<?= $categorias['pk_id_categorias']; ?>" class="btn btn-primary">
                                            Editar
                                        </a>
                                        <button class="btn btn-danger">Excluir</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <br>

                    <!-- TABELA COM ALGUMAS INFORMAÇÕES DAS FORMAS DE PAGAMENTO CADASTRADAS -->
                    <h3 class="title-tabela">Formas de Pagamento</h3>
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
                                        <button class="btn btn-success">Visualizar</button>
                                        <button class="btn btn-primary">Editar</button>
                                        <button class="btn btn-danger">Excluir</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>

            </div>

            <div class="panel-pendences">

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

            </div>
        </div>