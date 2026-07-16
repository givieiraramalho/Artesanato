<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Artesanatos Eloi</title>
    <link rel="stylesheet" href="style-produtos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
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

            <!-- IMAGENS/BOTÕES DO ENTRAR E DO CARRINHO -->
            <div class="actions">
                <button>
                    <a href="../login/login.php">
                        <img src="../img/person.svg" alt="Login">
                    </a>
                </button>

                <button>
                    <img src="../img/cart3.svg" alt="Carrinho">
                </button>
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

    <!--PARTE DOS CARDS DOS PRODUTOS-->
    <div class="card-container">
        <div class="product-card">
            <img src="../img/caminhao-madeira.png" alt="Imagem de um caminhão colorido de brinquedo feito de MDF" class="product-image">
            <div class="product-content">
                <h2 class="product-title">Caminhão de Brinquedo</h2>
                <p class="product-description">Caminhão artesanal é um brinquedo feito em MDF com pintura atóxica, ideal para estimular a criatividade e a diversão das crianças.</p>
                <div class="product-price">R$25.99</div>
                <button class="add-to-card">Ver Produto</button>
            </div>
        </div>

        <div class="product-card">
            <img src="../img/caminhao-teste.png" alt="Imagem de um caminhão com peças coloridas que giram feito em MDF" class="product-image">
            <div class="product-content">
                <h2 class="product-title">Caminhãozinho Gira-Gira</h2>
                <p class="product-description">Brinquedo educativo em MDF com peças giratórias coloridas, perfeito para brincadeiras criativas e interativas.</p>
                <div class="product-price">R$29.99</div>
                <button class="add-to-card">Ver Produto</button>
            </div>
        </div>

        <div class="product-card">
            <img src="../img/tremduplo-cinza.png" alt="Imagem de um trenzinho de 2 vagões coloridos feito em MDF" class="product-image">
            <div class="product-content">
                <h2 class="product-title">Trenzinho de Madeira</h2>
                <p class="product-description">Trenzinho artesanal de madeira com acabamento delicado e cores vibrantes, ideal para diversão e decoração infantil.</p>
                <div class="product-price">R$34.99</div>
                <button class="add-to-card">Ver Produto</button>
            </div>
        </div>
    </div>

    <!--<div class="product-card">
            <img src="../img/caminhao-teste.png" alt="Imagem de um caminhão colorido de brinquedo feito de MDF" class="product-image">
            <div class="product-content">
                <h2 class="product-title">Caminhão de Brinquedo</h2>
                <p class="product-description">Este brinquedo é realizado com MDF de qualidade e tintas atóxicas</p>
                <div class="product-price">R$29.99</div>
                <button class="add-to-card"><img src="../img/cart3.svg" alt=""></button>
                <button class="add-to-favorite"><img src="../img/heart.svg" alt=""></button>
            </div>
        </div>
    </div>-->

    <!-- PARTE FINAL DO SITE / FOOTER 
    <footer class="footer">
        <h3>Artesanatos Eloi</h3>
        <small>© 2026 - Todos os Direitos Reservados</small>
    </footer>-->

    <!--PARTE DE LINKS JAVASCRIPT-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>