<?php

require 'config.php';

$categoria = [];
$id = filter_input(INPUT_GET, 'id');

if ($id) {
    $sql = $pdo->prepare("SELECT * FROM categorias WHERE pk_id_categorias = :id");
    $sql->bindValue(':id', $id);
    $sql->execute();

    if ($sql->rowCount() > 0) {
        $categoria = $sql->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "
    <script>
        alert('Não existe uma categoria com o id indicado');
        window.location.href = '../admin/pagina-inicial-adm.php';
    </script>
    ";

        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edição - Categorias dos Produtos</title>
    <link rel="stylesheet" href="../categorias/style-categorias.css">
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
        <form action="../banco/edit-action-categoria.php" method="POST" class="form-categoria">
            <h1>Editar Categoria</h1>
            <input type="hidden" name="id" value="<?= $categoria['pk_id_categorias'] ?>">
            <h2>Nome da Categoria</h2>
            <input type="text" name="namecategoria" id="namecategoria" class="input" value="<?= $categoria['nome_categoria'] ?>" required>
            <br>
            <button type="submit" class="btn-submit">Atualizar Categoria</button>

        </form>
    </div>

</body>

</html>