<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Artesanatos Eloi</title>
    <link rel="stylesheet" href="../admin/css/style-admin.css">
</head>

<body>

    <!-- PARTE DOS FORMULÁRIOS -->
    <div class="container-login">
        <div class="form-login">
            <h1>Login</h1>
            <button type="button" id="btn-adm" class="btn-submit">Admin</button>
            <button type="button" id="btn-funcionario" class="btn-submit">Funcionários</button>

            <form id="form-adm" action="../banco/login-adm.php" method="POST" hidden>
                <h1 class="title-form">Admin</h1>
                <h2>Email</h2>
                <input type="email" name="email" class="input" placeholder="Digite seu Email" autocomplete="off" required>
                <h2>Senha</h2>
                <input type="password" name="senha" class="input" placeholder="Digite uma Senha" required>
                <a href="#" class="forget-password">Esqueceu a Senha?</a>

                <button type="submit" class="btn-cadastro">Entrar</button>
                <a href="../admin/admin.php?acessoadm=artesanatos6578" class="link-cadastro">Não possui conta? Cadastre-se</a>
            </form>

            <form id="form-funcionario" action="../banco/login-func.php" method="POST" hidden>
                <h1 class="title-form">Funcionários</h1>
                <h2>Email</h2>
                <input type="email" name="email" class="input" placeholder="Digite seu Email" autocomplete="off" required>
                <h2>Senha</h2>
                <input type="password" name="senha" class="input" placeholder="Digite uma Senha" required>
                <a href="#" class="forget-password">Esqueceu a Senha?</a>

                <button type="submit" class="btn-cadastro">Entrar</button>
                <a href="../admin/admin.php?acessoadm=artesanatos6578" class="link-cadastro">Não possui conta? Cadastre-se</a>
            </form>
        </div>

        <script src="../admin/js/script-cadastro.js"></script>

</body>

</html>