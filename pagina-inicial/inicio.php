<?php

require '../banco/config.php';

session_start();

if (isset($_SESSION['sucesso_login'])) {
    echo "<script>alert('" . addslashes($_SESSION['sucesso_login']) . "');</script>";
    unset($_SESSION['sucesso_login']);
}

$sql = $pdo->query("SELECT * FROM categorias WHERE pk_id_categorias = 2");

$usuarioLogado = isset($_SESSION['user_id']);

// Inicializa a lista de produtos
$lista = [];

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
$lista = $sql->fetchAll(PDO::FETCH_ASSOC);
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

        <h1 class="title-gallery">Galeria de Produtos</h1>

        <div class="carousel-container">

            <div class="gallery">

                <div class="content">
                    <img src="../img/caminhaozinho-branco.png" alt="Foto de um Brinquedo de Caminhão de Madeira"
                        class="img-produtos">
                    <h3 class="h3">Caminhão de Madeira</h3>
                    <textarea class="p-produtos" readonly>Um brinquedo artesanal para incentivar a criatividade</textarea>
                    <a href="detalhes-galeria.php?produto=caminhao-madeira" class="ver-produto">Ver Produto</a>
                </div>

                <div class="content">
                    <img src="../img/xilofone-infantil.png" alt="Foto de um xilofone infantil colorido com duas baquetas com pontas vermelha redonda"
                        class="img-produtos">
                    <h3 class="h3">Xilofone Infantil</h3>
                    <textarea class="p-produtos" readonly>O xilofone infantil é um instrumento musical de percussão que estimula o desenvolvimento musical das crianças.</textarea>
                    <a href="detalhes-galeria.php?produto=xilofone-infantil" class="ver-produto">Ver Produto</a>
                </div>

                <div class="content">
                    <img src="../img/caminhaozinho.png" alt="Foto de um Brinquedo de Caminhão de Carga de Madeira"
                        class="img-produtos">
                    <h3 class="h3">Caminhãozinho Gira-Gira</h3>
                    <textarea class="p-produtos" readonly>Um brinquedo artesanal feito com muito carinho e dedicação para todas as idades</textarea>
                    <a href="detalhes-galeria.php?produto=caminhaozinho-gira-gira" class="ver-produto">Ver Produto</a>
                </div>

                <div class="content">
                    <img src="../img/qb-patrulhacaninarosa.png" alt="Foto de um Brinquedo de Quebra Cabeça da Patrulha Canina com duas personagens e o fundo do quebra cabeça rosa"
                        class="img-produtos">
                    <h3 class="h3">Quebra-Cabeça Patrulha Canina</h3>
                    <textarea class="p-produtos" readonly>Um quebra cabeça para testar as habilidades da criançada</textarea>
                    <a href="detalhes-galeria.php?produto=quebra-cabeca-patrulha-canina" class="ver-produto">Ver Produto</a>
                </div>

                <div class="content">
                    <img src="../img/montanha-russa-educativa.png" alt="Foto de um brinquedo com arames coloridos de diversos formatos e cores com formas geométricas"
                        class="img-produtos">
                    <h3 class="h3">Montanha-Russa Educativa</h3>
                    <textarea class="p-produtos" readonly>Um brinquedo que desenvolve a coordenação motora ao guiar as peças pelo percurso</textarea>
                    <a href="detalhes-galeria.php?produto=montanha-russa-educativa" class="ver-produto">Ver Produto</a>
                </div>

                <div class="content">
                    <img src="../img/lig4-mdf.png" alt="Foto de um brinquedo em mdf com diversos círculos no meio e peças circulares veremlha e preta que acompanha o jogo"
                        class="img-produtos">
                    <h3 class="h3">Connect 4</h3>
                    <textarea class="p-produtos" readonly>O Connect 4 é um jogo clássico de estratégia para dois jogadores</textarea>
                    <a href="detalhes-galeria.php?produto=connect-4" class="ver-produto">Ver Produto</a>
                </div>

                <div class="content">
                    <img src="../img/tremduplo-branco (1).png" alt="Foto de um Brinquedo de Trenzinho de Madeira"
                        class="img-produtos">
                    <h3 class="h3">Trenzinho Colorido</h3>
                    <textarea class="p-produtos" readonly>Um trenzinho colorido com tintas atóxicas para incentivar ainda mais a brincadeira da criançada</textarea>
                    <a href="detalhes-galeria.php?produto=trenzinho-colorido" class="ver-produto">Ver Produto</a>
                </div>

                <div class="content">
                    <img src="../img/xadrez-dama.png" alt="Foto de um tabuleiro de xadrez e dama com as peças de xadrez e dama em cima do tabuleiro"
                        class="img-produtos">
                    <h3 class="h3">Xadrez e Dama</h3>
                    <textarea class="p-produtos" readonly>Um jogo para testar as habilidades e estratégias de pensamento</textarea>
                    <a href="detalhes-galeria.php?produto=xadrez-dama" class="ver-produto">Ver Produto</a>
                </div>

                <div class="content">
                    <img src="../img/qb-frozen.png" alt="Foto de um quebra cabeça da Frozen com diversos personagens e com o fundo azul"
                        class="img-produtos">
                    <h3 class="h3">Quebra-Cabeça Frozen</h3>
                    <textarea class="p-produtos" readonly>Um jogo educativo que desenvolve a concentração e a coordenação motora das crianças</textarea>
                    <a href="detalhes-galeria.php?produto=quebra-cabeca-frozen" class="ver-produto">Ver Produto</a>
                </div>

                <div class="content">
                    <img src="../img/campo-futebol.png" alt="Foto de um Brinqudo de Futebol com diversos bonequinhos e o fundo verde"
                        class="img-produtos">
                    <h3 class="h3">Futebol Maluco</h3>
                    <textarea class="p-produtos" readonly>Um novo estilo de jogar futebol com o o jogo de futebol maluco</textarea>
                    <a href="detalhes-galeria.php?produto=futebol-maluco" class="ver-produto">Ver Produto</a>
                </div>
            </div>
        </div>

        <div class="carousel-dots">
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

</body>

<script>
    const usuarioLogado = <?= $usuarioLogado ? 'true' : 'false'; ?>;
</script>

<script src="../pagina-inicial/script.js"></script>

</html>