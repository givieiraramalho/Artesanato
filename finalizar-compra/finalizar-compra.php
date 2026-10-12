<?php
session_start();

require '../banco/config.php';

//Vendo se possui um id do usuário, caso não tenha, vai para a parte do login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

//Resgatando o id do usuário
$idUsuario = $_SESSION['user_id'];

$totalCompra = 0;
$produtosCarrinho = [];

foreach ($_SESSION['carrinho'] as $id_produto => $quantidade) {

    //Parte que regata os produtos que a pessoa colocou no carrinho
    $sql = $pdo->prepare("SELECT * FROM produtos WHERE pk_id_produtos = ?");

    $sql->execute([$id_produto]);

    $produto = $sql->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        continue;
    }

    $subtotal = $produto['preco_produto'] * $quantidade;

    $totalCompra += $subtotal;

    $produto['quantidade'] = $quantidade;
    $produto['subtotal'] = $subtotal;

    $produtosCarrinho[] = $produto;
}

//Parte que resgata o endereço
$sqlEndereco = $pdo->prepare("SELECT * FROM endereco WHERE id_usuario = ?");

$sqlEndereco->execute([$_SESSION['user_id']]);

$endereco = $sqlEndereco->fetch(PDO::FETCH_ASSOC);

//Definindo um valor inicial para o frete
$frete = 0;

//Pegando a parte do estado dentro da tabela de endereço
$estado = $endereco['estado'];

//Definido o frete por região
switch ($estado) {

    // Região Sudeste
    case 'SP':
    case 'RJ':
    case 'MG':
    case 'ES':
        $frete = 15.00;
        break;

    // Região Sul
    case 'PR':
    case 'SC':
    case 'RS':
        $frete = 20.00;
        break;

    // Região Centro-Oeste
    case 'GO':
    case 'MT':
    case 'MS':
    case 'DF':
        $frete = 25.00;
        break;

    // Região Nordeste
    case 'AL':
    case 'BA':
    case 'CE':
    case 'MA':
    case 'PB':
    case 'PE':
    case 'PI':
    case 'RN':
    case 'SE':
        $frete = 30.00;
        break;

    // Região Norte
    case 'AC':
    case 'AM':
    case 'AP':
    case 'PA':
    case 'RO':
    case 'RR':
    case 'TO':
        $frete = 35.00;
        break;

    // Segurança
    default:
        $frete = 40.00;
        break;
}

if ($totalCompra >= 100) {
    $frete = 0;
}

//Soma do total da compra com o frete
$totalFinal = $totalCompra + $frete;

//Parte que resgata as informações da pessoa
$sqlPessoa = $pdo->prepare("SELECT * FROM pessoa WHERE id_usuario = ?");

$sqlPessoa->execute([$_SESSION['user_id']]);

$pessoa = $sqlPessoa->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra - Artesanatos Eloi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">-->
    <link rel="stylesheet" href="style-finalizar-compra.css">
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


    <div class="content-carrinho">

        <div class="card-carrinho">

            <h1>Finalizar Compra</h1>

            <a href="../carrinho-compras/carrinho-compras.php" class="link-redirect">
                ← Voltar para o carrinho
            </a>

            <div class="card-content">

                <!-- PRODUTOS -->
                <div class="checkout-secao">

                    <h2>Produtos comprados</h2>

                    <?php foreach ($produtosCarrinho as $produto): ?>

                        <div class="item-carrinho">

                            <div class="imagem-produto-carrinho">
                                <img src="mostrar-imagem.php?id=<?= $produto['pk_id_produtos']; ?>" alt="Produto">
                            </div>

                            <div class="info-produto-carrinho">
                                <h2><?= $produto['nome_produto']; ?></h2>

                                <div class="quantidade-carrinho">
                                    Quantidade:
                                    <strong><?= $produto['quantidade']; ?></strong>
                                </div>

                            </div>

                            <div class="subtotal-carrinho">

                                <span>Subtotal</span>

                                <strong>
                                    R$ <?= number_format($produto['subtotal'], 2, ',', '.'); ?>
                                </strong>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>


                <!-- ENDEREÇO -->
                <div class="checkout-secao">

                    <h2>Endereço de entrega</h2>

                    <div class="checkout-informacao">

                        <strong>
                            <?= htmlspecialchars($endereco['nome_rua']); ?>,
                            <?= htmlspecialchars($endereco['numero_casa']); ?>
                        </strong>

                        <p>
                            <?= htmlspecialchars($endereco['nome_bairro']); ?>
                        </p>

                        <p>
                            <?= htmlspecialchars($endereco['cidade']); ?>/<?= htmlspecialchars($endereco['estado']); ?>
                        </p>

                        <p>
                            CEP: <?= htmlspecialchars($endereco['cep']); ?>
                        </p>

                        <?php if (!empty($endereco['apartamento'])): ?>
                            <p>
                                Apartamento:
                                <?= htmlspecialchars($endereco['apartamento']); ?>
                            </p>
                        <?php endif; ?>

                        <?php if (!empty($endereco['referencia'])): ?>
                            <p>
                                Referência:
                                <?= htmlspecialchars($endereco['referencia']); ?>
                            </p>
                        <?php endif; ?>

                    </div>

                </div>


                <!-- DESTINATÁRIO -->
                <div class="checkout-secao">

                    <h2>Para quem será entregue?</h2>

                    <div class="checkout-informacao">

                        <strong><?= htmlspecialchars($_SESSION['user_nome']); ?></strong>

                        <p>
                            Telefone:
                            <?= htmlspecialchars($pessoa['telefone']); ?>
                        </p>

                        <p>
                            Email:
                            <?= htmlspecialchars($pessoa['email']); ?>
                        </p>

                    </div>

                </div>


                <!-- FRETE -->
                <div class="checkout-secao">

                    <h2>Entrega</h2>

                    <div class="checkout-informacao">

                        <p>
                            <strong>Frete padrão</strong>
                        </p>

                        <p>
                            Prazo estimado: 5 a 8 dias úteis
                        </p>

                        <p>
                            Valor do frete: R$ <?= number_format($frete, 2, ',', '.'); ?>
                        </p>

                    </div>

                </div>


                <!-- PAGAMENTO -->
                <div class="checkout-secao">

                    <h2>Forma de pagamento</h2>

                    <div class="formas-pagamento">

                        <label>
                            <input
                                type="radio"
                                name="pagamento"
                                value="cartao-debito">

                            Cartão de Débito
                        </label>

                        <label>
                            <input
                                type="radio"
                                name="pagamento"
                                value="cartao-credito">

                            Cartão de Crédito
                        </label>

                    </div>

                </div>


                <!-- RESUMO -->
                <div class="resumo-carrinho checkout-resumo">

                    <div class="checkout-valores">

                        <div>
                            <span>Subtotal:</span>
                            <strong>R$ <?= number_format($totalCompra, 2, ',', '.'); ?></strong>
                        </div>

                        <div>
                            <span>Frete:</span>
                            <strong>R$ <?= number_format($frete, 2, ',', '.'); ?></strong>
                        </div>

                        <div class="checkout-total">
                            <span>Total:</span>
                            <strong> R$ <?= number_format($totalFinal, 2, ',', '.'); ?></strong>
                        </div>

                    </div>

                    <form action="../finalizar-pagamento/checkout.php" method="POST">
                        <button type="submit" class="btn-continuar">
                            Finalizar pedido
                        </button>
                    </form>

                </div>

            </div>

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
                    <a href="https://www.facebook.com/brinqdcorserranegra/"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://wa.me/5519995233724"><i class="fab fa-whatsapp"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
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
                        <li><a href="#">Novidades</a></li>
                        <li><a href="#">Promoções</a></li>
                        <li><a href="#">Mais Vendidos</a></li>
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



    <script src="../js/script-geral.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>