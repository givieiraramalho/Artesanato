<?php

session_start();

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias dos Produtos - Artesanatos Eloi</title>
    <link rel="stylesheet" href="style-categorias.css">
</head>

<body>
    <div class="header">

        <!-- MENU LATERAL MOBILE -->
        <div class="menu-mobile" id="menuMobile">
            <div class="menu-mobile-conteudo">

                <button class="fechar-menu" onclick="fecharMenu()">
                    ✕
                </button>

                <a href="../admin/pagina-inicial-adm.php">Início</a>
                <a href="../editar-produtos/edit-products.php">Editar Produtos</a>

                <div class="menu-mobile-titulo">
                    Cadastro
                </div>

                <a href="../cadastrar-produtos/cadastro-produtos.php">
                    Produtos
                </a>

                <a href="../categorias/categorias.php">
                    Categorias
                </a>

                <a href="../pagamento/forma-pagamento.php">
                    Forma de Pagamento
                </a>

                <a href="#">Analisar Vendas</a>

            </div>
        </div>

        <!-- PARTE DE CIMA DO TOP BAR -->
        <div class="top-bar">
            <a href="javascript:void(0)" onclick="abrirMenu()">
                <img src="../img/artes-removebg-preview.png"
                    class="img-logo"
                    alt="Logo">
            </a>

            <!-- BARRA DE PESQUISA -->
            <div class="search">
                <input type="text" placeholder="Buscar produtos...">
                <button class="search-btn">
                    <img src="../img/search.svg" alt="Pesquisar"></button>
            </div>

            <!-- IMAGENS/BOTÕES DAS NOTIFICAÇÕES E DAS CONFIGURAÇÕES -->
            <div class="actions">
                <div class="dropdown-button">
                    <button class="button-dropdown">
                        <img src="../img/person.svg" alt="Login">
                    </button>

                    <!-- CONTEÚDO DENTRO DO BOTÃO DE LOGIN -->
                    <div class="conteudo-dropdown">
                        <a href="#">Página do Admin <?= htmlspecialchars($_SESSION['user_nome'] ?? '') ?></a>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="../banco/logout-admin.php">Sair</a>
                        <?php endif; ?>
                    </div>
                </div>

                <button><a href=""><img src="../img/bell.svg" alt="Notificações"></a></button>
            </div>
        </div>

        <!-- PARTE DE BAIXO DO TOP BAR COM OS LINKS DE NAVEGAÇÃO -->
        <div class="bottom-bar">
            <a href="../admin/pagina-inicial-adm.php" class="link-animado">Início</a>
            <a href="../editar-produtos/edit-products.php" class="link-animado">Editar Produtos</a>
            <div class="dropdown">
                <a href="#" class="link-animado">Cadastro</a>

                <div class="dropdown-menu">
                    <a href="../cadastrar-produtos/cadastro-produtos.php">Produtos</a>
                    <a href="../categorias/categorias.php">Categorias</a>
                    <a href="../pagamento/forma-pagamento.php">Forma de Pagamento</a>
                    <a href="../funcionarios/cadastro-funcionario.php">Funcionários</a>
                </div>
            </div>
            <a href="../admin/analisar-vendas/analisar-vendas.php" class="link-animado">Analisar Vendas</a>
        </div>
    </div>
    </div>

    <div class="container-categoria">
        <form action="../banco/cadatro-categoria.php" method="POST" class="form-categoria">
            <h1>Cadastrar Categoria</h1>
            <h2>Nome da Categoria</h2>
            <input type="text" name="namecategoria" id="namecategoria" class="input" placeholder="Ex: Caminhões" autocomplete="off" required>
            <br>
            <button type="submit" class="btn-submit">Cadastrar</button>

        </form>
    </div>

    <!-- PARTE DO JAVASCRIPT -->
    <script src="../js/script-adm.js"></script>

</body>

</html>