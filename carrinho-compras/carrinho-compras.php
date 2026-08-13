<?php
//Iniciando um sessão que irá armazenar os produtos que o usuário escolher
session_start();

//Resgatando as configurações iniciais do banco de dados
require '../banco/config.php';

//Inicialização do Carrinho
// Caso ainda não exista um carrinho na memória, irá criar um carrinho vazio
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

//Ações do Carrinho
//Ação de Adicionar um Produto no Carrinho
if (isset($_GET['adicionar'])) {

    $idProduto = $_GET['adicionar'];

    if (!isset($_SESSION['carrinho'][$idProduto])) {
        $_SESSION['carrinho'][$idProduto] = 1;
    } else {
        $_SESSION['carrinho'][$idProduto]++;
    }

    header("Location: carrinho-compras.php");
    exit;

    //Ação de diminuir um produto que já foi adicionado no carrinho
} elseif (isset($_GET['diminuir'])) {

    $idProduto = $_GET['diminuir'];

    if (isset($_SESSION['carrinho'][$idProduto])) {

        $_SESSION['carrinho'][$idProduto]--;

        if ($_SESSION['carrinho'][$idProduto] <= 0) {
            unset($_SESSION['carrinho'][$idProduto]);
        }
    }

    header("Location: carrinho-compras.php");
    exit;

    //Ação de remover um produto do carrinho ao apertar o botão de remover
} elseif (isset($_GET['remover'])) {

    $idProduto = $_GET['remover'];

    unset($_SESSION['carrinho'][$idProduto]);

    header("Location: carrinho-compras.php");
    exit;
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
            <a href="../produtos/produtos.php" class="link-redirect">← Voltar para a Vitrine</a>

            <?php if (empty($_SESSION['carrinho'])): ?>
                <p style="margin-top: 20px;">Seu carrinho está totalmente vazio.</p>
            <?php else: ?>
                <div class="card-content">
                    <table>
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Quantidade</th>
                                <th>Preço Unitário</th>
                                <th>Subtotal</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_da_compra = 0;

                            // Passa item por item que está guardado na sessão
                            foreach ($_SESSION['carrinho'] as $id_produto => $quantidade):

                                $sql = $pdo->prepare("
                    SELECT *
                    FROM produtos
                    WHERE pk_id_produtos = :id");

                                $sql->bindValue(":id", $id_produto);
                                $sql->execute();

                                $produto = $sql->fetch(PDO::FETCH_ASSOC);

                                if (!$produto) {
                                    continue;
                                }

                                $subtotal = $produto['preco_produto'] * $quantidade;
                                $total_da_compra += $subtotal;
                            ?>
                                <tr>
                                    <td><?php echo $produto['nome_produto']; ?></td>
                                    <td>
                                        <!-- Botão de Menos (-) envia o comando para a própria página -->
                                        <a class="btn" href="carrinho-compras.php?diminuir=<?php echo $produto['pk_id_produtos']; ?>">-</a>

                                        <strong style="margin: 0 10px;"><?php echo $quantidade; ?></strong>

                                        <!-- Botão de Mais (+) usa a mesma lógica do "Comprar Agora" -->
                                        <a class="btn" href="carrinho-compras.php?adicionar=<?php echo $produto['pk_id_produtos']; ?>">+</a>
                                    </td>
                                    <td>R$ <?php echo number_format($produto['preco_produto'], 2, ',', '.'); ?></td>
                                    <td>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                                    <td>
                                        <!-- Botão de Remover -->
                                        <a class="btn btn-remover" href="carrinho-compras.php?remover=<?php echo $produto['pk_id_produtos']; ?>">Remover</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            <tr>
                                <td colspan="3" style="text-align: right; font-weight: bold;">TOTAL DA COMPRA:</td>
                                <td colspan="1" style="font-size: 1.2em; font-weight: bold; color: green;">
                                    R$ <?php echo number_format($total_da_compra, 2, ',', '.'); ?>
                                </td>
                                <td colspan="1"><a class="btn btn-success" href="">Continuar Compra</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
<?php endif; ?>

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