<?php

//Responsável por resgatar o código principal de conexão com o banco de dados
require '../banco/config.php';

session_start();

$categoria = filter_input(INPUT_GET, 'categoria', FILTER_VALIDATE_INT);

$listaProdutos = [];

if ($categoria && $categoria > 1) {

    $sql = $pdo->prepare("
        SELECT p.*
        FROM produtos p
        INNER JOIN produto_categoria pc
            ON p.pk_id_produtos = pc.id_produto
        WHERE p.status_produto = 1
        AND pc.id_categoria = ?
    ");

    $sql->execute([$categoria]);
} else {

    $sql = $pdo->query("
        SELECT *
        FROM produtos
        WHERE status_produto = 1
    ");
}

if ($sql->rowCount() > 0) {
    $listaProdutos = $sql->fetchAll(PDO::FETCH_ASSOC);
}

$categoriaSelecionada = $_GET['categoria'] ?? null;

$categorias = [
    1 => 'Todos',
    2 => 'Recomendados',
    3 => 'Destaques',
    4 => 'Promoções',
    5 => 'Mais Vendidos'
];

$nomeCategoria = $categoriaSelecionada !== null && isset($categorias[$categoriaSelecionada])
    ? $categorias[$categoriaSelecionada]
    : null;

$temFiltro = $categoriaSelecionada !== null && $categoriaSelecionada != 1;


// Verifica se foi digitado algo na pesquisa
if (!empty($_POST['search'])) {

    // Prepara o termo de pesquisa
    $data = "%" . trim($_POST['search']) . "%";

    // Pesquisa pelo ID, nome ou descrição do produto
    $sql = $pdo->prepare("
        SELECT *
        FROM produtos
        WHERE pk_id_produtos LIKE ?
        OR nome_produto LIKE ?
        OR descricao LIKE ?
        ORDER BY pk_id_produtos ASC
    ");

    $sql->execute([$data, $data, $data]);
} else {

    // Se não houver pesquisa, lista todos os produtos
    $sql = $pdo->query("
        SELECT *
        FROM produtos
        ORDER BY pk_id_produtos ASC
    ");
}

// Recupera os produtos encontrados
$listaProdutos = $sql->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Artesanatos Eloi</title>
    <link rel="stylesheet" href="style-produtos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="header">

        <!-- PARTE DE CIMA DO TOP BAR -->
        <div class="top-bar">

            <!-- LOGO -->
            <a href="../pagina-inicial/inicio.php" class="logo-link" id="logo-menu">
                <img src="../img/artes-removebg-preview.png" class="img-logo" alt="Logo">
            </a>


            <!-- BARRA DE PESQUISA -->
            <form method="POST" class="search">

                <input
                    type="text"
                    name="search"
                    id="pesquisar"
                    placeholder="Buscar produtos..."
                    value="<?= isset($_POST['search'])
                                ? htmlspecialchars($_POST['search'], ENT_QUOTES, 'UTF-8')
                                : '' ?>">

                <button type="submit" class="search-btn">
                    <img src="../img/search.svg" alt="Pesquisar">
                </button>

            </form>

            <!-- BOTÃO DE ENTRAR/CADASTRAR -->
            <div class="actions">

                <div class="dropdown-button">

                    <button class="button-dropdown">
                        <img src="../img/person.svg" alt="Login">
                    </button>

                    <div class="conteudo-dropdown">
                        <a href="../cadastro/cadastro.php">Cadastro</a>
                        <a href="../login/login.php">Login</a>

                        <a href="../pagina-usuario/pagina-usuario.php">
                            Página do Usuário
                            <?= htmlspecialchars($_SESSION['user_nome'] ?? '') ?>
                        </a>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="../banco/logout-usuario.php">Sair</a>
                        <?php endif; ?>
                    </div>

                </div>

                <!-- CARRINHO -->
                <a href="../carrinho-compras/carrinho-compras.php">
                    <button>
                        <img src="../img/cart3.svg" alt="Carrinho">
                    </button>
                </a>

            </div>

        </div>

        <!-- MENU DE NAVEGAÇÃO -->
        <div class="bottom-bar" id="menu-mobile">

            <a href="../pagina-inicial/inicio.php" class="link-animado">
                Início
            </a>

            <a href="../produtos/produtos.php" class="link-animado">
                Produtos
            </a>

            <div class="dropdown">
                <a href="#" class="link-animado" id="categorias-mobile">
                    Categorias
                </a>

                <div class="dropdown-menu">
                    <a href="../produtos/produtos.php?categoria=1">Todos</a>
                    <a href="../produtos/produtos.php?categoria=2">Recomendados</a>
                    <a href="../produtos/produtos.php?categoria=3">Destaques</a>
                    <a href="../produtos/produtos.php?categoria=4">Promoções</a>
                    <a href="../produtos/produtos.php?categoria=5">Mais Vendidos</a>
                </div>
            </div>

            <a href="../sobre/sobre.php" class="link-animado">
                Sobre
            </a>

        </div>
    </div>

    <!-- SLIDES DE APRESENTAÇÃO DA EMPRESA/PRODUTOS -->
    <?php if (!$temFiltro): ?>

        <section class="banner">
            <div class="banner-content">
                <h1>Brinquedos Artesanais em MDF</h1>

                <p>
                    Criados com amor e dedicação para trazer alegria e diversão para
                    as crianças. Produtos sustentáveis, seguros e de alta qualidade.
                </p>
            </div>
        </section>

    <?php endif; ?>

    <!-- PARTE DE FILTROS DE PRODUTOS -->
    <section class="area-filtros">

        <div class="cabecalho-filtros">
            <span class="linha-decorativa"></span>

            <div>
                <h2>Encontre o que procura</h2>
                <p>Explore nossa coleção de produtos artesanais</p>
            </div>

            <span class="linha-decorativa"></span>
        </div>

        <div class="filtros">

            <a href="produtos.php?categoria=1"
                class="filtro <?= ($categoriaSelecionada == 1 || $categoriaSelecionada === null) ? 'ativo' : ''; ?>">
                Todos
            </a>

            <a href="produtos.php?categoria=2"
                class="filtro <?= ($categoriaSelecionada == 2) ? 'ativo' : ''; ?>">
                Recomendados
            </a>

            <a href="produtos.php?categoria=3"
                class="filtro <?= ($categoriaSelecionada == 3) ? 'ativo' : ''; ?>">
                Destaques
            </a>

            <a href="produtos.php?categoria=4"
                class="filtro <?= ($categoriaSelecionada == 4) ? 'ativo' : ''; ?>">
                Promoções
            </a>

            <a href="produtos.php?categoria=5"
                class="filtro <?= ($categoriaSelecionada == 5) ? 'ativo' : ''; ?>">
                Mais Vendidos
            </a>

        </div>

        <?php if ($temFiltro): ?>

            <div class="titulo-categoria">
                <span></span>
                <h1>Produtos - <?= htmlspecialchars($nomeCategoria); ?></h1>
                <span></span>
            </div>

        <?php endif; ?>

    </section>


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

                <div class="product-info">

                    <h2 class="product-title">
                        <?= $produtos['nome_produto'] ?>
                    </h2>

                    <p class="product-description">
                        <?= $produtos['descricao'] ?>
                    </p>

                    <div class="product-price">
                        R$ <?= $produtos['preco_produto'] ?>
                    </div>

                    <div class="action-button">
                        <a class="btn-product" href="../ver-produtos/pagina-produto.php?id=<?= $produtos['pk_id_produtos'] ?>">Ver Produto</a>

                        <a class="btn-comprar" href="../carrinho-compras/carrinho-compras.php?adicionar=<?php echo $produtos['pk_id_produtos']; ?>">Comprar Agora</a>
                    </div>
                </div>

            </div>

        <?php endforeach; ?>
    </div>

    <!-- PARTE FINAL DO SITE / FOOTER -->
    <footer>
        <div class="content-footer">
            <div class="top">
                <div class="logo-details">
                    <span class="logo_name">Artesanatos Eloi</span>
                </div>
                <div class="media-icons">
                    <a href="https://www.facebook.com/brinqdcorserranegra/"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://wa.me/5519995233724"><i class="fab fa-whatsapp"></i></a>
                    <a href="https://www.instagram.com/brinqdcor?stkn=MXJsanliOWFnc215NA=="><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="link-boxes">
                <ul class="boxes">
                    <li class="link_name">Sobre a Empresa</li>
                    <li><a href="../pagina-inicial/inicio.php">Início</a></li>
                    <li><a href="https://wa.me/5519995233724">Contatos</a></li>
                    <li><a href="https://maps.app.goo.gl/QoNvQpyp3u9aoydS8">Endereços</a></li>
                    <li><a href="../sobre/sobre.php">Nossa História</a></li>
                </ul>
                <div class="link-boxes">
                    <ul class="boxes">
                        <li class="link_name">Produtos</li>
                        <li><a href="../produtos/produtos.php">Todos os Produtos</a></li>
                        <li><a href="../produtos/produtos.php?categoria=2">Recomendados</a></li>
                        <li><a href="../produtos/produtos.php?categoria=3">Destaques</a></li>
                        <li><a href="../produtos/produtos.php?categoria=4">Promoções</a></li>
                        <li><a href="../produtos/produtos.php?categoria=5">Mais Vendidos</a></li>
                    </ul>
                    <ul class="boxes input-box">
                        <li class="link_name">Inscreva-se</li>
                        <li><input type="text" placeholder="Digite seu email"></li>
                        <li><input type="button" value="Inscreva-se"></li>
                    </ul>
                </div>
            </div>
            <div class="bottom-details">
                <div class="bottom_text">
                    <span class="copyright_text">
                        Copyright &#169; 2026 - Artesanatos Eloi. <br>
                        Todos os Direitos Reservados
                    </span>
                </div>
            </div>
    </footer>

    <!--PARTE DE LINKS JAVASCRIPT-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="../js/script-geral.js"></script>
</body>

</html>