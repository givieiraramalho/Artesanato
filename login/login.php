<?php
session_start();

if (isset($_SESSION['sucesso_cadastro'])) {
    echo "<script>alert('" . addslashes($_SESSION['sucesso_cadastro']) . "');</script>";
    unset($_SESSION['sucesso_cadastro']);
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Artesanatos Eloi</title>
    <link rel="stylesheet" href="style-login.css">
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

    <div class="container-login">
        <form action="../banco/login-action.php" method="POST" class="form-login">
            <img src="../img/logo-ae.png" alt="">
            <h1>Login</h1>
            <h2>Email</h2>
            <input type="email" name="email" class="input" placeholder="Nome do Usuário" autocomplete="off">
            <h2>Senha</h2>
            <input type="password" name="senha" class="input" placeholder="Senha">
            <a href="../forget-password/forget-password.php" class="forget-password">Esqueci minha senha?</a>
            <button type="submit" class="btn-submit">Entrar</button>
            <a href="../cadastro/cadastro.php" class="link-cadastro">Não possui conta? Cadastra-se</a>
        </form>
    </div>

    <?php
    if (isset($_SESSION['erro_login'])) {
        $mensagem = addslashes($_SESSION['erro_login']);

        echo "<script>
            alert('$mensagem');
          </script>";

        unset($_SESSION['erro_login']);
    }
    ?>

    <script src="../js/script-geral.js"></script>

</body>

</html>