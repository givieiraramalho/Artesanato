<?php

// Iniciando a sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificando se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    echo "<script>
        alert('Você precisa fazer login para visualizar os detalhes do produto.');
        window.location.href = '../login/login.php';
    </script>";

    exit;
}

// Cadastro dos produtos
$produtos = [
    'caminhao-madeira' => [
        'nome_produto' => 'Caminhão de Madeira',
        'imagem' => '../img/caminhao-brinquedo.png',
        'descricao' => 'Um brinquedo artesanal que estimula a imaginação e a criatividade das crianças. Com seu design inspirado nos caminhões tradicionais, é ideal para inventar aventuras e tornar a brincadeira ainda mais divertida.'
    ],

    'xilofone-infantil' => [
        'nome_produto' => 'Xilofone Infantil',
        'imagem' => '../img/xilofone-infantil.png',
        'descricao' => 'Uma opção divertida para despertar o interesse das crianças pela música. O xilofone infantil incentiva a exploração dos sons, a criatividade e a coordenação motora por meio de brincadeiras musicais.'
    ],

    'caminhaozinho-gira-gira' => [
        'nome_produto' => 'Caminhãozinho Gira-Gira',
        'imagem' => '../img/caminhao-carga.png',
        'descricao' => 'Um brinquedo criativo que transforma momentos de diversão em grandes aventuras. Seu formato divertido convida as crianças a explorar movimentos, criar histórias e desenvolver a imaginação enquanto brincam.'
    ],

    'quebra-cabeca-patrulha-canina' => [
        'nome_produto' => 'Quebra-Cabeça Patrulha Canina',
        'imagem' => '../img/qb-patrulhacaninarosa.png',
        'descricao' => 'Ideal para os pequenos fãs da Patrulha Canina! Este quebra-cabeça proporciona momentos de diversão enquanto estimula a concentração, o raciocínio lógico e a percepção visual das crianças.'
    ],

    'montanha-russa-educativa' => [
        'nome_produto' => 'Montanha-Russa Educativa',
        'imagem' => '../img/montanha-russa-educativa.png',
        'descricao' => 'Uma brincadeira educativa que combina diversão e aprendizado. A montanha-russa incentiva as crianças a acompanhar trajetos, explorar movimentos e desenvolver a coordenação motora e o raciocínio de maneira lúdica.'
    ],

    'connect-4' => [
        'nome_produto' => 'Connect 4',
        'imagem' => '../img/lig4-mdf.png',
        'descricao' => 'Um jogo divertido que desafia os participantes a conectar quatro peças em sequência. Ideal para compartilhar momentos em família, desenvolver o pensamento estratégico e estimular a atenção de forma descontraída.'
    ],

    'trenzinho-colorido' => [
        'nome_produto' => 'Trenzinho Colorido',
        'imagem' => '../img/trem-duplo.png',
        'descricao' => 'Um trenzinho encantador que convida as crianças a embarcar em aventuras cheias de imaginação. Seu visual colorido torna a brincadeira mais alegre e estimula a criatividade na criação de novas histórias.'
    ],

    'xadrez-dama' => [
        'nome_produto' => 'Xadrez e Dama',
        'imagem' => '../img/xadrez-dama.png',
        'descricao' => 'Dois jogos clássicos reunidos em uma única opção de entretenimento. O xadrez e a dama ajudam a desenvolver o raciocínio lógico, a concentração e o planejamento, proporcionando momentos divertidos para todas as idades.'
    ],

    'quebra-cabeca-frozen' => [
        'nome_produto' => 'Quebra-Cabeça Frozen',
        'imagem' => '../img/qb-frozen.png',
        'descricao' => 'Uma opção encantadora para os fãs de Frozen! Este quebra-cabeça transforma o momento de brincar em uma oportunidade de exercitar a concentração, a memória visual e a resolução de desafios.'
    ],

    'futebol-maluco' => [
        'nome_produto' => 'Futebol Maluco',
        'imagem' => '../img/campo-futebol.png',
        'descricao' => 'Diversão garantida para quem gosta de futebol e desafios! Este brinquedo convida os participantes a testar suas habilidades, aprimorar a coordenação motora e compartilhar momentos animados em uma partida diferente.'
    ]

];


// Identificando o produto selecionado
$idProduto = $_GET['produto'] ?? '';

// Verifica se o produto existe na lista
if (!isset($produtos[$idProduto])) {
    http_response_code(404);
    exit('Produto não encontrado.');
}


// Recupera os dados do produto selecionado
$produto = $produtos[$idProduto];

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= htmlspecialchars($produto['nome_produto']) ?> - Artesanatos Eloi
    </title>

    <!-- CSS da página de detalhes de produtos -->
    <link rel="stylesheet" href="../ver-produtos/style-pagina-produto.css">

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbuj+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer">

</head>

<body>
    <!-- PARTE DO HEADER -->
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


    <!-- CARD COM OS DETALHES DO PRODUTO -->
    <main class="content-produto">

        <section class="card-produto">

            <!-- LINK PARA VOLTAR -->
            <a
                href="inicio.php"
                class="link-redirect">

                ← Voltar para a Galeria

            </a>


            <!-- ÁREA PRINCIPAL DO PRODUTO -->
            <div class="produto-detalhes">


                <!-- LADO ESQUERDO -->
                <div class="produto-esquerda">

                    <!-- NOME DO PRODUTO -->
                    <h1 class="title-produto">

                        <?= htmlspecialchars($produto['nome_produto']) ?>

                    </h1>


                    <!-- IMAGEM DO PRODUTO -->
                    <div class="imagem-container">

                        <img
                            class="img-produto"
                            src="<?= htmlspecialchars($produto['imagem']) ?>"
                            alt="<?= htmlspecialchars($produto['nome_produto']) ?>">

                    </div>

                </div>


                <!-- LADO DIREITO -->
                <div class="produto-direita">

                    <!-- DESCRIÇÃO -->
                    <div class="descricao-container">

                        <h2 class="descricao-produto">

                            Descrição do Produto

                        </h2>


                        <p class="text-descricao-produto">

                            <?= htmlspecialchars($produto['descricao']) ?>

                        </p>

                    </div>


                    <!-- BOTÃO PARA VOLTAR À GALERIA -->
                    <div class="buttons-actions">

                        <a
                            href="inicio.php"
                            class="btn-add-cart">

                            Voltar para a Galeria

                        </a>

                    </div>
                </div>
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

    <!-- SCRIPTS GERAIS -->
    <script src="../js/script-geral.js"></script>

</body>

</html>