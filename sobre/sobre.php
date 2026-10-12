<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre - Artesanatos Eloi</title>
    <link rel="stylesheet" href="../sobre/style-sobre.css">
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

    <main class="sobre">

        <!-- CABEÇALHO DA PÁGINA -->
        <header class="sobre-header">
            <div class="container">
                <span class="sobre-header__label">Nossa história</span>

                <h1>
                    Uma história construída
                    <strong>com as próprias mãos.</strong>
                </h1>

                <p>
                    Há mais de 20 anos, a Artesanatos Eloi transforma madeira
                    em brinquedos e momentos que fazem parte da infância de
                    muitas famílias.
                </p>
            </div>
        </header>


        <!-- INTRODUÇÃO -->
        <section class="historia-intro">
            <div class="container historia-intro__grid">

                <div class="historia-intro__titulo">
                    <span class="section-label">O começo</span>
                    <h2>Como tudo começou?</h2>
                </div>

                <div class="historia-intro__texto">
                    <p>
                        A Artesanatos Eloi nasceu de um sonho simples:
                        trabalhar com aquilo que sempre trouxe alegria —
                        os brinquedos.
                    </p>

                    <p>
                        O que começou como uma pequena produção familiar,
                        dentro de uma casa em Serra Negra, cresceu com o
                        passar dos anos sem perder aquilo que sempre esteve
                        no centro de tudo: o cuidado com cada brinquedo.
                    </p>
                </div>

            </div>
        </section>


        <!-- HISTÓRIA - CRIADOR ARTESANATOS ELOI -->
        <section class="historia-bloco">
            <div class="container historia-bloco__grid">

                <div class="historia-bloco__imagem">
                    <img
                        src="../img/foto-homenagem.jpeg"
                        alt="Antônio Ramalho, conhecido como Seu Teco">
                </div>

                <div class="historia-bloco__conteudo">
                    <span class="section-label">Os primeiros anos</span>

                    <h2>
                        Seu Teco e o sonho
                        de uma família
                    </h2>

                    <p>
                        Conhecido por todos como Seu Teco, Antônio Ramalho
                        começou essa história ao lado de sua esposa,
                        Rosa Ramalho.
                    </p>

                    <p>
                        Nos fundos de sua casa, em Serra Negra, os dois
                        deram os primeiros passos para construir aquilo
                        que viria a se tornar a Artesanatos Eloi.
                    </p>

                    <p>
                        Com algumas máquinas e um pequeno espaço de produção,
                        começaram a fabricar, montar e pintar os brinquedos.
                        Cada etapa era feita com atenção, desde a madeira
                        até o acabamento e a embalagem.
                    </p>
                </div>

            </div>
        </section>


        <!-- A FÁBRICA -->
        <section class="historia-fabrica">
            <div class="container">

                <div class="historia-fabrica__imagem">
                    <img
                        src="../img/foto-fabrica.png"
                        alt="Fábrica onde começou a história da Artesanatos Eloi">
                </div>

                <div class="historia-fabrica__texto">
                    <span class="section-label">Uma história que cresceu</span>

                    <h2>
                        Do pequeno galpão
                        para novas gerações
                    </h2>

                    <p>
                        O pequeno espaço de produção foi crescendo junto
                        com o sonho da família. O trabalho ganhou novos
                        funcionários, novos brinquedos e, principalmente,
                        novos caminhos.
                    </p>

                    <p>
                        Mas a essência continuou a mesma: produzir brinquedos
                        de qualidade e proporcionar às crianças a oportunidade
                        de brincar, imaginar e criar suas próprias histórias.
                    </p>
                </div>

            </div>
        </section>


        <!-- DIAS ATUAIS -->
        <section class="historia-atual">
            <div class="container">

                <div class="historia-atual__cabecalho">
                    <span class="section-label">Dias atuais</span>

                    <h2>
                        O legado continua
                    </h2>

                    <p>
                        Hoje, os filhos dão continuidade ao trabalho iniciado
                        pelo Seu Teco, mantendo viva uma história construída
                        dentro da própria família.
                    </p>
                </div>


                <div class="locais">

                    <article class="local">
                        <span class="local__numero">01</span>

                        <h3>Artesanatos Eloi</h3>

                        <p>
                            Nossa fábrica, onde são produzidos os brinquedos
                            e mantida a tradição da Artesanatos Eloi.
                        </p>

                        <address>
                            Rua Belisária Maria de Jesus, 431<br>
                            Serra Negra - SP
                        </address>
                    </article>


                    <article class="local">
                        <span class="local__numero">02</span>

                        <h3>Brinqdcor</h3>

                        <p>
                            Nossa loja no centro de Serra Negra, onde você
                            encontra brinquedos e produtos para toda a família.
                        </p>

                        <address>
                            Rua Coronel Pedro Penteado, 166<br>
                            Centro — Serra Negra - SP
                        </address>
                    </article>


                    <article class="local">
                        <span class="local__numero">03</span>

                        <h3>A Caçulinha</h3>

                        <p>
                            No endereço onde essa história começou, a antiga
                            fábrica continua fazendo parte da nossa trajetória.
                        </p>

                        <address>
                            Rua Santa Carolina, 142<br>
                            Bairro das Palmeiras — Serra Negra - SP
                        </address>
                    </article>

                </div>

            </div>
        </section>


        <!-- ENCERRAMENTO -->
        <section class="sobre-final">
            <div class="container">

                <p class="sobre-final__frase">
                    "Mais de 20 anos fazendo parte
                    da infância de tantas famílias."
                </p>

                <span>
                    Artesanatos Eloi
                </span>

            </div>
        </section>

    </main>

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

    <!-- PARTE DO JAVASRIPT -->
     <script src="../js/script-geral.js"></script>

</body>

</html>