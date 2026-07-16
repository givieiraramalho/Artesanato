<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Artesanatos Eloi</title>
    <link rel="stylesheet" href="style-admin.css">
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

            <!-- IMAGENS/BOTÕES DO ENTRAR E DO CARRINHO -->
            <div class="actions">
                <button><a href="../login/login.php"><img src="../img/person.svg" alt="Login"></a></button>
                <button><img src="../img/cart3.svg" alt="Carrinho"></button>
            </div>
        </div>

        <!-- PARTE DE BAIXO DO TOP BAR COM OS LINKS DE NAVEGAÇÃO -->
        <div class="bottom-bar">
            <a href="" class="link-animado">Início</a>
            <a href="" class="link-animado">Editar Produtos</a>
            <div class="dropdown">
                    <a href="#" class="link-animado">Cadastro</a>

                    <div class="dropdown-menu">
                        <a href="#">Produtos</a>
                        <a href="#">Categorias</a>
                        <a href="#">Faixa Etária</a>
                        <a href="#">Forma de Pagamento</a>
                        <a href="#">Funcionários</a>
                    </div>
                </div> 
            <a href="" class="link-animado">Analisar Vendas</a>
            <a href="" class="link-animado"></a>
        </div>
    </div>
    </div>

    <div class="container-login">
        <div class="form-login">
            <h1>Cadastro</h1>
            <button type="button" id="btn-adm" class="btn-submit">Admin</button>
            <button type="button" id="btn-funcionario" class="btn-submit">Funcionários</button>

            <form id="form-adm" action="../banco/cadastro-adm.php" method="POST" hidden>
                <h1 class="title-form">Admin</h1>
                <h2>Nome</h2>
                <input type="text" name="nome" class="input" placeholder="Nome Completo" required>
                <h2>Email</h2>
                <input type="email" name="email" class="input" placeholder="Digite seu Email" required>
                <h2>Telefone</h2>
                <input type="text" name="telefone" id="telefone" class="input" maxlength="15" placeholder="(19)99999-9999" required>
                <h2>Senha</h2>
                <input type="password" name="senha" pattern="(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}" title="A senha deve conter pelo menos 8 caracteres, uma letra maiúscula e um caractere especial." class="input" placeholder="Digite uma Senha" required>
                <h2>Repita sua Senha</h2>
                <input type="password" name="repetir_senha" pattern="(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}" title="A senha deve conter pelo menos 8 caracteres, uma letra maiúscula e um caractere especial." class="input" placeholder="Repita a Senha" required>
                <button type="submit" class="btn-cadastro">Cadastrar</button>
                <a href="../admin/login-adm.php">Já possui conta? Entrar</a>
            </form>

            <form id="form-funcionario" action="../banco/cadastro-func.php" method="POST" hidden>
                <h1 class="title-form">Funcionários</h1>
                <h2>Nome</h2>
                <input type="text" name="nome" class="input" placeholder="Nome Completo" required>
                <h2>Email</h2>
                <input type="email" name="email" class="input" placeholder="Digite seu Email" required>
                <h2>Senha</h2>
                <input type="password" name="senha" pattern="(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}" title="A senha deve conter pelo menos 8 caracteres, uma letra maiúscula e um caractere especial." class="input" placeholder="Digite uma Senha" required>
                <h2>Repita sua Senha</h2>
                <input type="password" name="repetir_senha" pattern="(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}" title="A senha deve conter pelo menos 8 caracteres, uma letra maiúscula e um caractere especial." class="input" placeholder="Repita a Senha" required>

                <button type="submit" class="btn-cadastro">Cadastrar</button>
                <a href="../admin/login-adm.php">Já possui conta? Entrar</a>
            </form>
        </div>

</body>

</html>

<?php
// AQUI FICA ARMAZENADA A CHAVE SECRETA PARA O ACESSO DESTA PÁGINA
$chave_secreta = "artesanatos6578";

// VERIFICAÇÃO DA URL PARA VER SE A PESSOA ACESSOU A URL CORRETA
if (!isset($_GET['acessoadm']) || $_GET['acessoadm'] !== $chave_secreta) {
    // CASO A PESSOA ACESSE A URL DE FORMA ERRADA, DARÁ UM ERRO 404 - NOT FOUND
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 Not Found</h1>";
    echo "A página solicitada não foi encontrada no servidor.";
    exit;
}

?>

<script src="../admin/script-cadastro.js"></script>