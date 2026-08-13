<?php

session_start();

require 'config.php';

$email = filter_input(INPUT_POST, 'email');
$senha = filter_input(INPUT_POST, 'senha');

$sql = $pdo->prepare("
    SELECT id, nome, senha
    FROM cadastro
    WHERE email = :email
    LIMIT 1
");

$sql->bindValue(':email', $email);
$sql->execute();

$user = $sql->fetch(PDO::FETCH_ASSOC);

if ($user) {

    if (password_verify($senha, $user['senha'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nome'] = $user['nome'];

        $_SESSION['sucesso_login'] = "Login realizado com sucesso!";

        header("Location: ../pagina-inicial/inicio.php");
        exit();

    } else {

        $_SESSION['erro_login'] = "Usuário e/ou senha incorretos!";

        header("Location: ../login/login.php"); 
        exit();

    }

} else {

    $_SESSION['erro_login'] = "Usuário não encontrado!";

    header("Location: ../login/login.php"); 
    exit();

}
