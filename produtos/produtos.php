<?php

//Responsável por resgatar o código principal de conexão com o banco de dados
require '../banco/config.php';

//Fazendo o Select da tabela Produtos
$listaProdutos = [];

$sql = $pdo->query("SELECT * FROM produtos WHERE status_produto = 1");
if ($sql->rowCount() > 0) {
    $listaProdutos = $sql->fetchAll(PDO::FETCH_ASSOC);
}
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
            <a href="">
                <img src="../img/artes-removebg-preview.png" class="img-logo" alt="Logo">
            </a>

            <!-- BARRA DE PESQUISA -->
            <div class="search">
                <input type="text" placeholder="Buscar produtos...">

                <button class="search-btn">
                    <img src="../img/search.svg" alt="Pesquisar">
                </button>
            </div>

            <!-- BOTÃO DE ENTRAR/CADASTRAR -->
            <div class="actions">

                <div class="dropdown-button">
                    <button class="button-dropdown">
                        <img src="../img/person.svg" alt="Login">
                    </button>

                    <div class="conteudo-dropdown">
                        <a href="../cadastro/cadastro.php">Cadastro</a>
                        <a href="../login/login.php">Login</a>
                        <a href="#">Página do Usuário</a>
                    </div>
                </div>

                <!-- BOTÃO DO CARRINHO DE COMPRAS -->
                <a href="../carrinho-compras/carrinho-compras.php">
                    <button>
                        <img src="../img/cart3.svg" alt="Carrinho">
                    </button>
                </a>
            </div>
        </div>

        <!-- PARTE DE BAIXO DO TOP BAR COM OS LINKS DE NAVEGAÇÃO -->
        <div class="bottom-bar">
            <a href="../pagina-inicial/inicio.php" class="link-animado">Início</a>
            <a href="../produtos/produtos.php" class="link-animado">Produtos</a>
            <div class="dropdown">
                <a href="#" class="link-animado">Categorias</a>

                <div class="dropdown-menu">
                    <a href="#">Todos</a>
                    <a href="#">Recomendados</a>
                    <a href="#">Destaques</a>
                    <a href="#">Promoções</a>
                    <a href="#">Mais Vendidos</a>
                </div>
            </div>
            <a href="../sobre/sobre.php" class="link-animado">Sobre</a>
        </div>
    </div>

    <!-- SLIDES DE APRESENTAÇÃO DA EMPRESA/PRODUTOS -->
    <section class="banner">
        <div class="banner-content">
            <h1>Brinquedos Artesanais em MDF</h1>
            <p>Criados com amor e dedicação para trazer alegria e diversão para
                as crianças. Produtos sustentáveis, seguros e de alta qualidade.</p>
        </div>
    </section>

    <!-- PARTE DE FILTROS DE PRODUTOS -->
    <div class="filters">
        <button class="filter active">
            Todos
        </button>
        <button class="filter">
            Recomendados
        </button>
        <button class="filter">
            Destaques
        </button>
        <button class="filter">
            Promoções
        </button>
        <button class="filter">
            Mais Vendidos
        </button>
    </div>

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
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="link-boxes">
                <ul class="boxes">
                    <li class="link_name">Sobre a Empresa</li>
                    <li><a href="#">Início</a></li>
                    <li><a href="#">Contatos</a></li>
                    <li><a href="#">Endereços</a></li>
                    <li><a href="#">Nossa História</a></li>
                </ul>
                <div class="link-boxes">
                    <ul class="boxes">
                        <li class="link_name">Produtos</li>
                        <li><a href="#">Início</a></li>
                        <li><a href="#">Todos os Produtos</a></li>
                        <li><a href="#">Novidades</a></li>
                        <li><a href="#">Promoções</a></li>
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
                        Copyright &#169; 2026 - Artesanatos Eloi.
                        Todos os Direitos Reservados
                    </span>
                </div>
            </div>
    </footer>

    <!--PARTE DE LINKS JAVASCRIPT-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>