<?php
session_start();

if (isset($_SESSION['sucesso_login'])) {
    echo "<script>alert('" . addslashes($_SESSION['sucesso_login']) . "');</script>";
    unset($_SESSION['sucesso_login']);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial - Artesanatos Eloi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="header">

        <!-- PARTE DE CIMA DO TOP BAR -->
        <div class="top-bar">
            <a href=""><img src="../img/artes-removebg-preview.png" class="img-logo" alt="Logo"></a>

            <!-- BARRA DE PESQUISA -->
            <div class="search">
                <input type="text" placeholder="Buscar produtos...">
                <button class="search-btn">
                    <img src="../img/search.svg" alt="Pesquisar"></button>
            </div>

            <!-- BOTÃO DE ENTRAR/CADASTRAR -->
            <div class="actions">

                <div class="dropdown-button">
                    <button class="button-dropdown">
                        <img src="../img/person.svg" alt="Login">
                    </button>

                    <!-- CONTEÚDO DENTRO DO BOTÃO DE LOGIN -->
                    <div class="conteudo-dropdown">
                        <a href="../cadastro/cadastro.php">Cadastro</a>
                        <a href="../login/login.php">Login</a>
                        <a href="../pagina-usuario/pagina-usuario.php">Página do Usuário <?= htmlspecialchars($_SESSION['user_nome'] ?? '') ?></a>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="../banco/logout-usuario.php">Sair</a>
                        <?php endif; ?>
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
    </div>

    <!-- SLIDES DE APRESENTAÇÃO DA EMPRESA/PRODUTOS -->
    <section class="banner">
        <div class="banner-content">
            <h1>Brinquedos Artesanais em MDF</h1>
            <p>Criados com amor e dedicação para trazer alegria e diversão para
                as crianças. Produtos sustentáveis, seguros e de alta qualidade.</p>
        </div>
    </section>

    <!-- PARTE DAS ENTREGAS -->
    <div class="marca">
        <div class="certificado">
            <img src="../img/heart.svg" alt="Ícone de Coração">
            <h3>Feito com Amor</h3>
            <p>Cada peça artesanal é feita com muito cuidado e carinho</p>
        </div>

        <div class="certificado">
            <img src="../img/shield.svg" alt="Ícone de Segurança/Escudo">
            <h3>100% Seguro</h3>
            <p>Tintas Atóxicas e materiais certificados</p>
        </div>

        <div class="certificado">
            <img src="../img/truck.svg" alt="Ícone de Caminhão">
            <h3>Entrega Rápida</h3>
            <p>Enviamos nossos produtos para todo o Brasil</p>
        </div>

        <div class="certificado">
            <img src="../img/star.svg" alt="Ícone de Estrela">
            <h3>Qualidade Premium</h3>
            <p>Os produtos são relizados com MDF de primeira linha</p>
        </div>
    </div>

    <!-- AMOSTRA DE ALGUNS BRINQUEDOS -->
    <section class="conhece-brinquedos">

        <div class="gallery">

            <div class="produtos-left">
                <div class="content">
                    <img src="../img/caminhaozinho-branco.png" alt="Foto de um Brinquedo de Caminhão de Madeira"
                        class="img-produtos">
                    <h3 class="h3">Caminhão de Madeira</h3>
                    <p class="p-produtos">Um brinquedo artesanal feito com muito carinho e dedicação para todas as
                        crianças</p>
                    <button class="ver-produto" onclick="mostrarAlerta()">Ver Produto</button>
                </div>
            </div>

            <div class="produtos-left">
                <div class="content">
                    <img src="../img/caminhaozinho.png" alt="Foto de um Brinquedo de Caminhão de Carga de Madeira"
                        class="img-produtos">
                    <h3 class="h3">Caminhãozinho Gira-Gira</h3>
                    <p class="p-produtos">Um brinquedo artesanal feito com muito carinho e dedicação para todas as
                        crianças
                    </p>
                    <button class="ver-produto" onclick="mostrarAlerta()">Ver Produto</button>
                </div>
            </div>

            <div class="produtos-left">
                <div class="content">
                    <img src="../img/tremduplo-branco (1).png" alt="Foto de um Brinquedo de Trenzinho de Madeira"
                        class="img-produtos">
                    <h3 class="h3">Trenzinho Colorido</h3>
                    <p class="p-produtos">Um brinquedo artesanal feito com muito carinho e dedicação para todas as
                        crianças
                    </p>
                    <button class="ver-produto" onclick="mostrarAlerta()">Ver Produto</button>
                </div>
            </div>
        </div>

        <!--<button class="ver-mais"><a href="../produtos/produtos.php">Mais Produtos</a></button>-->
    </section>

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
                        Copyright &#169; 2026 - Artesanatos Eloi. <br>
                        Todos os Direitos Reservados
                    </span>
                </div>
            </div>
    </footer>

</body>

<script src="../pagina-inicial/script.js"></script>

</html>