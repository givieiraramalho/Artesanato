<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre - Artesanatos Eloi</title>
    <link rel="stylesheet" href="style-sobre.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="header">
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
    </div>

    <section class="banner">
        <div class="banner-content">
            <h1>Artesanatos Eloi - Uma história de tradição e confiança</h1>
            <p>A Artesanatos Eloi é uma empresa de confiança que atua há mais de 20 anos no mercado com brinquedos de qualidade e reconhecimento no mercado de brinquedos em MDF</p>
        </div>
    </section>

    <div class="history">
        <h2>Como começou?</h2>
        <p>A Artesanatos Eloi começou de um sonho grande de um pai que precisava sustentar a família e queria começar a trabalhar com uma coisa que sempre amava. Brinquedos!</p>
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

</body>

</html>