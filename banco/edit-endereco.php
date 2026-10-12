<?php

require 'config.php';

$endereco = [];
$id = filter_input(INPUT_GET, 'id');

if ($id) {
    $sql = $pdo->prepare("SELECT * FROM endereco WHERE id_usuario = :id");
    $sql->bindValue(':id', $id);
    $sql->execute();

    if ($sql->rowCount() > 0) {
        $endereco = $sql->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "
    <script>
        alert('Não existe um endereço com o id indicado');
        window.location.href = '../pagina-usuario/pagina-usuario.php';
    </script>
    ";

        exit;
    }
} else {
    header("Location: ../pagina-usuario/pagina-usuario.php");
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Endereço - Artesanatos Eloi</title>
    <link rel="stylesheet" href="../pagina-forms-endereco/style-forms-endereco.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
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

    <!-- FORMULÁRIO ONDE UM USUÁRIO IRÁ COLOCAR AS INFORMAÇÕES DO ENDEREÇO ONDE RESIDE -->
    <div class="container-forms">
        <form action="edit-action-endereco.php" method="POST" class="form-cadastro-endereco">
            <h1 class="title-form">Editar Endereço</h1>
            <p class="description-forms">
                Edite aqui as informações necessárias do endereço cadastrado
            </p>

            <!-- NOME DA RUA -->
             <input type="hidden" name="id" value="<?= $endereco["id_usuario"]; ?>">
            <h2 class="info-usuario">Nome da Rua</h2>
            <input type="text" name="rua" class="input" id="rua" value="<?= $endereco["nome_rua"]; ?>" autocomplete="off" required>

            <!-- DATA DO BAIRRO -->
            <h2 class="info-usuario">Nome do Bairro</h2>
            <input type="text" name="bairro" class="input" id="bairro" value="<?= $endereco["nome_bairro"]; ?>" autocomplete="off" required>

            <!-- NOME DA CIDADE -->
            <h2 class="info-usuario">Nome da Cidade</h2>
            <input type="text" name="cidade" class="input" id="cidade" value="<?= $endereco["cidade"]; ?>" autocomplete="off" required>

            <!-- CEP -->
            <h2 class="info-usuario">CEP</h2>
            <input type="text" name="cep" id="cep" class="input" maxlength="9" value="<?= $endereco["cep"]; ?>" autocomplete="off"
                required>

            <div class="linha-dupla">

                <!-- ESTADOS -->
               <div class="campo">
                    <h2 class="info-usuario">Estado</h2>

                    <input
                        type="text"
                        name="estado"
                        class="input"
                        id="estado"
                        value="<?= $endereco["estado"]; ?>"
                        autocomplete="off"
                        required>
                </div>

                <!-- NÚMERO DA CASA -->
                <div class="campo">
                    <h2 class="info-usuario">Nº da Residência</h2>

                    <input
                        type="number"
                        name="casa"
                        class="input"
                        id="numero"
                        value="<?= $endereco["numero_casa"]; ?>"
                        autocomplete="off"
                        required>
                </div>

            </div>

            <!-- NÚMERO DO APARTAMENTO/BLOCO (OPCIONAL) -->
            <h2 class="info-usuario label-desabilitado" id="labelApartamento">
                Nº do Apartamento
            </h2>

            <input
                type="text"
                name="apartamento"
                id="apartamento"
                class="input input-apartamento"
                value="<?= $endereco["apartamento"]; ?>"
                autocomplete="off"
                disabled>

            <div class="checkbox-apartamento">
                <input type="checkbox" id="moraApartamento">

                <label for="moraApartamento">
                    Moro em apartamento
                </label>
            </div>


            <!-- REFERÊNCIA (OPCIONAL) -->
            <h2 class="info-usuario">Referência (Opcional)</h2>
            <textarea name="referencia" class="input" value="<?= $endereco["referencia"]; ?>" autocomplete="off"></textarea>

            <!-- BOTÃO RESPONSÁVEL POR ENVIAR AS INFORMAÇÕES DO FORMULÁRIO -->
            <button type="submit" class="btn-submit">
                Atualizar Endereço
            </button>
        </form>
    </div>

    <script src="../pagina-forms-endereco/js/jquery-4.0.0.js"></script>
    <script src="../pagina-forms-endereco/js/func-cep.js"></script>
    <script src="../pagina-forms-endereco/js/script-forms-endereco.js"></script>
