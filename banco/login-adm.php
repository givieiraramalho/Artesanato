<?php

require 'config.php';
session_start();

// pega dados do formulário
$email = filter_input(INPUT_POST, 'email');
$senha = filter_input(INPUT_POST, 'senha');

// busca usuário pelo email
$sql = $pdo->prepare("SELECT email, senha FROM admin WHERE email = :email");
$sql->bindValue(':email', $email);
$sql->execute();

$user = $sql->fetch();

//Nesta parte, ele irá validar o login e irá verificar se está correto
if ($user && password_verify($senha, $user['senha'])) {

    $_SESSION['user_id'] = $user['id'];

    echo "
    <script>
        alert('Login realizado com sucesso!');
        window.location.href = 'http://localhost/artesanatos-eloi/admin/pagina-inicial-adm.php';
    </script>
    ";

    exit;

} else {

    echo "
    <script>
        alert('Atenção! O email e/ou a senha estão incorretos');
        window.history.back();
    </script>
    ";

    exit;
}

?>