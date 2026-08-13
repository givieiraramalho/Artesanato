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
    <link rel="stylesheet" href="../pagina-usuario/style-pagina-usuario.css">
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