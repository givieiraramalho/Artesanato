<?php
session_start();

require '../banco/config.php';

if (isset($_GET['adicionar'])) {

    $id_produto = (int) $_GET['adicionar'];

    // Quantidade escolhida na página do produto
    $quantidade_adicionar = isset($_GET['quantidade'])
        ? (int) $_GET['quantidade']
        : 1;


    // Impede quantidade inválida
    if ($quantidade_adicionar < 1) {

        $quantidade_adicionar = 1;

    }


    // Busca o produto
    $sql = $pdo->prepare("
        SELECT *
        FROM produtos
        WHERE pk_id_produtos = :id
    ");

    $sql->bindValue(":id", $id_produto);

    $sql->execute();

    $produto = $sql->fetch(PDO::FETCH_ASSOC);


    if ($produto) {

        // Quantidade que já está no carrinho
        $quantidade_atual =
            $_SESSION['carrinho'][$id_produto] ?? 0;


        // Estoque disponível
        $estoque = (int) $produto['estoque'];


        // Soma a quantidade escolhida
        $nova_quantidade =
            $quantidade_atual + $quantidade_adicionar;


        // Verifica o estoque
        if ($nova_quantidade <= $estoque) {

            $_SESSION['carrinho'][$id_produto] =
                $nova_quantidade;

        } else {

            $_SESSION['aviso_estoque'] =
                "A quantidade solicitada ultrapassa o estoque disponível.";
        }
    }


    header("Location: carrinho-compras.php");

    exit;
}


/* Diminuir quantidade */
if (isset($_GET['diminuir'])) {

    $id_produto = $_GET['diminuir'];

    if (isset($_SESSION['carrinho'][$id_produto])) {

        $_SESSION['carrinho'][$id_produto]--;

        // Se chegar a zero, remove do carrinho
        if ($_SESSION['carrinho'][$id_produto] <= 0) {

            unset($_SESSION['carrinho'][$id_produto]);
        }
    }

    header("Location: carrinho-compras.php");
    exit;
}

/* Remover produto */
if (isset($_GET['remover'])) {

    $id_produto = $_GET['remover'];

    if (isset($_SESSION['carrinho'][$id_produto])) {

        unset($_SESSION['carrinho'][$id_produto]);
    }

    header("Location: carrinho-compras.php");
    exit;
}


/* Mensagem de alerta do estoque */
if (isset($_SESSION['aviso_estoque'])) {

    echo "<script>
        alert('" . addslashes($_SESSION['aviso_estoque']) . "');
    </script>";

    unset($_SESSION['aviso_estoque']);
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras - Artesanatos Eloi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">-->
    <link rel="stylesheet" href="../carrinho-compras/style-carrinho.css">
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

    <!-- CARRINHO DE COMPRAS -->
    <div class="content-carrinho">
        <div class="card-carrinho">

            <h1 class="title-carrinho">Carrinho de Compras</h1>

            <a href="../produtos/produtos.php" class="link-redirect">
                ← Voltar para a Vitrine
            </a>

            <?php if (empty($_SESSION['carrinho'])): ?>

                <div class="carrinho-vazio">
                    <p>Seu carrinho está totalmente vazio.</p>

                    <a href="../produtos/produtos.php" class="btn-continuar">
                        Ver Produtos
                    </a>
                </div>

            <?php else: ?>

                <div class="card-content">

                    <?php
                    $total_da_compra = 0;

                    foreach ($_SESSION['carrinho'] as $id_produto => $quantidade):

                        $sql = $pdo->prepare("
                        SELECT *
                        FROM produtos
                        WHERE pk_id_produtos = :id
                    ");

                        $sql->bindValue(":id", $id_produto);
                        $sql->execute();

                        $produto = $sql->fetch(PDO::FETCH_ASSOC);

                        if (!$produto) {
                            continue;
                        }

                        $subtotal = $produto['preco_produto'] * $quantidade;
                        $total_da_compra += $subtotal;
                    ?>

                        <!-- PRODUTO DO CARRINHO -->
                        <div class="item-carrinho">

                            <!-- IMAGEM -->
                            <div class="imagem-produto-carrinho">
                                <img
                                    src="mostrar-imagem.php?id=<?= $produto['pk_id_produtos']; ?>"
                                    alt="<?php echo $produto['nome_produto']; ?>">
                            </div>

                            <!-- INFORMAÇÕES -->
                            <div class="info-produto-carrinho">

                                <h2>
                                    <?php echo $produto['nome_produto']; ?>
                                </h2>

                                <p class="preco-produto">
                                    R$
                                    <?php
                                    echo number_format(
                                        $produto['preco_produto'],
                                        2,
                                        ',',
                                        '.'
                                    );
                                    ?>
                                </p>

                                <!-- QUANTIDADE -->
                                <div class="quantidade-carrinho">

                                    <a
                                        class="btn-quantidade diminuir"
                                        href="carrinho-compras.php?diminuir=<?php echo $produto['pk_id_produtos']; ?>">
                                        -
                                    </a>

                                    <strong>
                                        <?php echo $quantidade; ?>
                                    </strong>

                                    <a
                                        class="btn-quantidade aumentar"
                                        href="carrinho-compras.php?adicionar=<?php echo $produto['pk_id_produtos']; ?>">
                                        +
                                    </a>

                                </div>

                            </div>

                            <!-- SUBTOTAL -->
                            <div class="subtotal-carrinho">

                                <span>Subtotal</span>

                                <strong>
                                    R$
                                    <?php
                                    echo number_format(
                                        $subtotal,
                                        2,
                                        ',',
                                        '.'
                                    );
                                    ?>
                                </strong>

                            </div>

                            <!-- REMOVER -->
                            <div class="acao-carrinho">

                                <a
                                    class="btn-remover"
                                    href="carrinho-compras.php?remover=<?php echo $produto['pk_id_produtos']; ?>">
                                    Remover
                                </a>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

                <!-- PARTE FINAL DO CARRINHO -->
                <div class="resumo-carrinho">

                    <div class="total-carrinho">

                        <span>Total da compra</span>

                        <strong>
                            R$
                            <?php
                            echo number_format(
                                $total_da_compra,
                                2,
                                ',',
                                '.'
                            );
                            ?>
                        </strong>

                    </div>

                    <a class="btn-continuar" href="">
                        Continuar Compra
                    </a>

                </div>

            <?php endif; ?>

        </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>