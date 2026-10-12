<?php

require 'config.php';

$token = $_GET["token"];

$token_hash = hash("sha256", $token);

$query = "SELECT * FROM cadastro
          WHERE reset_senha = ?";

$stmt = $pdo->prepare($query);

$stmt->execute([$token_hash]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user === null) {
    die("Token não encontrado");
}

if (strtotime($user["reset_expira_senha"]) <= time()) {
    die("O token expirou!");
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci minha Senha - Artesanatos Eloi</title>
    <link rel="stylesheet" href="../forget-password/style-password.css">
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
        <form action="process-reset-password.php" method="POST" class="form-login">
            <h1>Recuperar Senha</h1>
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <h2>Nova Senha</h2>
            <input type="password" name="senha" id="senha" pattern="(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}" title="A senha deve conter pelo menos 8 caracteres, uma letra maiúscula e um caractere especial" class="input" autocomplete="off">
            <h2>Repita a Senha</h2>
            <input type="password" name="confirmacao_senha" id="confirmacao_senha" pattern="(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}" title="A senha deve conter pelo menos 8 caracteres, uma letra maiúscula e um caractere especial" class="input" autocomplete="off">
            <!--<a href="../login/login.php" class="link-redirect">
                ← Voltar para o Login
            </a>-->
            <button type="submit" class="btn-submit">Redefinir Senha</button>
        </form>
    </div>

</body>

<script src="../pagina-inicial/script.js"></script>

</html>