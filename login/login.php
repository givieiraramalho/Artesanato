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

    <div class="container-login">
        <form action="../banco/login-action.php" method="POST" class="form-login">
            <img src="../img/logo-ae.png" alt="">
            <h1>Login</h1>
            <h2>Email</h2>
            <input type="text" name="email" class="input" placeholder="Nome do Usuário" autocomplete="none">
            <h2>Senha</h2>
            <input type="password" name="senha" class="input" placeholder="Senha">
            <a href="#" class="forget-password">Esqueci minha senha?</a>
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

</body>

</html>