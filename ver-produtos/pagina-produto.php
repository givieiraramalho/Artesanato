<?php

//Responsável por resgatar o código principal de conexão com o banco de dados
require '../banco/config.php';

//Responsável por resgatar o id através de um método get
$id = filter_input(INPUT_GET, 'id');

//Caso o id seja um id inválido, ele irá cair neste if e irá "morrer" e mostrar uma mensagem dizendo que é um produto inválido
if (!$id) {
    die("Produto inválido.");
}

//Aqui está fazendo um Select e filtrando o id do produto
$sql = $pdo->prepare("
    SELECT *
    FROM produtos
    WHERE pk_id_produtos = :id
");

$sql->bindValue(':id', $id);
$sql->execute();

$produto = $sql->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    die("Produto não encontrado.");
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Artesanatos Eloi</title>
    <link rel="stylesheet" href="style-pagina-produto.css">
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

    <!-- CARD DO PRODUTO -->
    <div class="content-produto">
        <div class="card-produto">

            <!-- LINK DE REDIRECIONAMENTO PARA A PÁGINA DE PRODUTOS -->
            <a href="../produtos/produtos.php" class="link-redirect">
                <- Voltar para Produtos
                    </a>

                    <!-- NOME DO PRODUTO -->
                    <h2 class="title-produto">
                        <?= htmlspecialchars($produto['nome_produto']) ?>
                    </h2>

                    <!-- IMAGEM DO PRODUTO -->
                    <img class="img-produto" src="mostrar-imagem.php?id=<?= $produto['pk_id_produtos'] ?>">

                    <!-- DESCRIÇÃO DO PRODUTO -->
                    <h2 class="descricao-produto">
                        Descrição do Produto
                    </h2>

                    <p class="text-descricao-produto">
                        <?= htmlspecialchars($produto['descricao']) ?>
                    </p>

                    <!-- PREÇO DO PRODUTO -->
                    <h2 class="preco-produto">
                        R$
                        <?= number_format($produto['preco_produto'], 2, ',', '.') ?>
                    </h2>

                    <!-- ESTOQUE DO PRODUTO -->
                    <p class="estoque-produto">
                        Estoque:
                        <?= htmlspecialchars($produto['estoque']) ?>
                    </p>

                    <!-- QUANTIDADE DO PRODUTO -->
                    <div class="btn-quantidade">
                        <button class="diminuir-produto">
                            -
                        </button>
                        <p class="quantidade-produto">
                            1
                        </p>
                        <button class="aumentar-quantidade">
                            +
                        </button>
                    </div>

                    <!-- BOTÃO DE ADICIONAR PRODUTO AO CARRINHO -->
                    <button class="btn-add-cart">
                        <a href="">
                        Adicionar ao Carrinho
                        </a>
                    </button>

                    <!-- BOTÃO DE COMPRAR AGORA -->
                    <button class="btn-comprar">
                        <a href="">
                        Comprar Agora
                        </a>
                    </button>

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

</body>

</html>