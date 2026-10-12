<?php

require 'config.php';

$pagamento = [];
$id = filter_input(INPUT_GET, 'id');

if ($id) {
    $sql = $pdo->prepare("SELECT * FROM pagamento WHERE pk_id_pagamento = :id");
    $sql->bindValue(':id', $id);
    $sql->execute();

    if ($sql->rowCount() > 0) {
        $pagamento = $sql->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "
    <script>
        alert('Não existe uma forma de pagamento com o id indicado');
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
    <title>Edição Forma de Pagamento - Artesanatos Eloi</title>
    <link rel="stylesheet" href="../pagamento/style-pagamento.css">
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

    <!-- PARTE DO FORMULÁRIO DE CADASTRO DAS FORMAS DE PAGAMENTO -->
    <div class="container-categoria">
        <form action="../banco/edit-action-pagamento.php" method="POST" class="form-categoria">
            <h1>Editar Forma de Pagamento</h1>
            <input type="hidden" name="id" value="<?= $pagamento['pk_id_pagamento'] ?>">
            <h2>Nome da Forma de Pagamento</h2>
            <input type="text" name="namepagamento" id="namepagamento" class="input" autocomplete="off" value="<?= $pagamento['nome_pagamento'] ?>" required>
            <br>
            <button type="submit" class="btn-submit">Editar</button>

        </form>
    </div>

</body>

</html>
