<?php

require '../banco/config.php';

//Fazendo o Select da tabela Produtos
$listaProdutos = [];

// Pesquisa
$pesquisa = $_POST['search'] ?? "";

// Filtro
$filtro = $_POST['filtro'] ?? "todos";

// Monta a consulta
$sql = "SELECT * FROM produtos WHERE 1=1";
$parametros = [];

// Se houver pesquisa
if (!empty($pesquisa)) {
    $sql .= " AND (
        pk_id_produtos LIKE ?
        OR nome_produto LIKE ?
        OR status_produto LIKE ?
    )";

    $busca = "%" . $pesquisa . "%";

    $parametros[] = $busca;
    $parametros[] = $busca;
    $parametros[] = $busca;
}

// Aplica o filtro
if ($filtro == "ativos") {
    $sql .= " AND status_produto = 1";
} elseif ($filtro == "desativados") {
    $sql .= " AND status_produto = 0";
}

// Ordenação
$sql .= " ORDER BY pk_id_produtos ASC";

// Executa
$stmt = $pdo->prepare($sql);
$stmt->execute($parametros);

$listaProdutos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produtos - Artesanatos Eloi</title>
    <link rel="stylesheet" href="style-edit.css">
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
                    placeholder="Buscar Produtos..."
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
            <a href="" class="link-animado"></a>
        </div>
    </div>
    </div>

    <!-- PARTE DA APRESENTAÇÃO DA EDIÇÃO DOS PRODUTOS -->
    <div class="inicio">
        <div class="cabecalho-dashboard">
            <h1 class="title">Painel Edição - Produtos</h1>
            <p class="subtitulo">
                Aqui será possível visualizar todos os produtos já cadastrados, fazer a edição e a exclusão deles
            </p>
        </div>

        <!-- PARTE DE FILTROS DE PRODUTOS -->
        <form method="POST">
            <div class="filters">

                <button type="submit"
                    name="filtro"
                    value="todos"
                    class="filter <?= (!isset($_POST['filtro']) || $_POST['filtro'] == 'todos') ? 'active' : '' ?>">
                    Todos os Produtos
                </button>

                <button type="submit"
                    name="filtro"
                    value="ativos"
                    class="filter <?= (isset($_POST['filtro']) && $_POST['filtro'] == 'ativos') ? 'active' : '' ?>">
                    Produtos Ativos
                </button>

                <button type="submit"
                    name="filtro"
                    value="desativados"
                    class="filter <?= (isset($_POST['filtro']) && $_POST['filtro'] == 'desativados') ? 'active' : '' ?>">
                    Produtos Desativados
                </button>

            </div>
        </form>


        <!-- PARTE DOS CARDS DOS PRODUTOS -->
        <div class="card-container">

            <?php foreach ($listaProdutos as $produtos): ?>

                <div class="product-card">

                    <div class="product-image-container">
                        <img
                            src="mostrar-imagem.php?id=<?= $produtos['pk_id_produtos']; ?>"
                            alt="<?= $produtos['descricao_imagem']; ?>"
                            class="product-image">
                    </div>

                    <div class="product-content">

                        <h2 class="product-title">
                            <?= $produtos['nome_produto']; ?>
                        </h2>

                        <p class="product-description">
                            <?= $produtos['descricao']; ?>
                        </p>

                        <h4 class="product-estoque">
                            Estoque: <?= $produtos['estoque']; ?>
                        </h4>

                        <div class="product-values">

                            <div class="product-price">
                                R$ <?= number_format($produtos['preco_produto'], 2, ',', '.'); ?>
                            </div>

                            <div class="product-discount">
                                <?= $produtos['desconto']; ?>% OFF
                            </div>

                        </div>

                        <div class="card-buttons">

                            <button type="button" class="btn btn-primary">
                                Editar
                            </button>

                            <button type="button" class="btn btn-danger">
                                Excluir
                            </button>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>


        <!--PARTE DO JAVASCRIPT-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>