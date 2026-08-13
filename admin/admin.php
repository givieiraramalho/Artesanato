<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Artesanatos Eloi</title>
    <link rel="stylesheet" href="../admin/css/style-admin.css">
</head>

<body>
<!-- PARTE DO FORMULÁRIO DE CADASTRO -->
    <div class="container-login">
        <div class="form-login">
            <h1>Cadastro</h1>
            <button type="button" id="btn-adm" class="btn-submit">Admin</button>
            <button type="button" id="btn-funcionario" class="btn-submit">Funcionários</button>

            <!-- CADASTRO DO ADMIN -->
            <form id="form-adm" action="../banco/cadastro-adm.php" method="POST" hidden>
                <h1 class="title-form">Admin</h1>
                <h2>Nome</h2>
                <input type="text" name="nome" class="input" placeholder="Nome Completo" autocomplete="off" required>
                <h2>Email</h2>
                <input type="email" name="email" class="input" placeholder="Digite seu Email" autocomplete="off" required>
                <h2>Telefone</h2>
                <input type="text" name="telefone" id="telefone" class="input" maxlength="15" placeholder="(19)99999-9999" autocomplete="off" required>
                <h2>Senha</h2>
                <input type="password" name="senha" pattern="(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}" title="A senha deve conter pelo menos 8 caracteres, uma letra maiúscula e um caractere especial." class="input" placeholder="Digite uma Senha" required>
                <h2>Repita sua Senha</h2>
                <input type="password" name="repetir_senha" pattern="(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}" title="A senha deve conter pelo menos 8 caracteres, uma letra maiúscula e um caractere especial." class="input" placeholder="Repita a Senha" required>
                <button type="submit" class="btn-cadastro">Cadastrar</button>
                <a href="../admin/login-adm.php" class="link-cadastro">Já possui conta? Entrar</a>
            </form>

            <!-- CADASTRO DO FUNCIONÁRIO -->
            <form id="form-funcionario" action="../banco/cadastro-func.php" method="POST" hidden>
                <h1 class="title-form">Funcionários</h1>
                <h2>Nome</h2>
                <input type="text" name="nome" class="input" placeholder="Nome Completo" autocomplete="off" required>
                <h2>Email</h2>
                <input type="email" name="email" class="input" placeholder="Digite seu Email" autocomplete="off" required>
                <h2>Senha</h2>
                <input type="password" name="senha" pattern="(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}" title="A senha deve conter pelo menos 8 caracteres, uma letra maiúscula e um caractere especial." class="input" placeholder="Digite uma Senha" required>
                <h2>Repita sua Senha</h2>
                <input type="password" name="repetir_senha" pattern="(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}" title="A senha deve conter pelo menos 8 caracteres, uma letra maiúscula e um caractere especial." class="input" placeholder="Repita a Senha" required>

                <button type="submit" class="btn-cadastro">Cadastrar</button>
                <a href="../admin/login-adm.php" class="link-cadastro">Já possui conta? Entrar</a>
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

<script src="../admin/js/script-cadastro.js"></script>