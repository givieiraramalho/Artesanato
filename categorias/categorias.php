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

                <!-- IMAGENS/BOTÕES DAS NOTIFICAÇÕES E DAS CONFIGURAÇÕES -->
                <div class="actions">
                    <button><a href=""><img src="../img/bell.svg" alt="Notificações"></a></button>
                    <button><img src="../img/gear.svg" alt="Configurações"></button>
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
                <a href="" class="link-animado">Analisar Vendas</a>
            </div>
        </div>
    </div>

    <div class="container-categoria">
        <form action="../banco/cadatro-categoria.php" method="POST" class="form-categoria">
            <h1>Cadastrar Categoria</h1>
            <h2>Nome da Categoria</h2>
            <input type="text" name="namecategoria" id="namecategoria" class="input" placeholder="Ex: Caminhões" required>
            <br>
            <button type="submit" class="btn-submit">Cadastrar</button>

        </form>
    </div>

</body>

</html>