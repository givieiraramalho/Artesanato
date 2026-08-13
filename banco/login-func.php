<?php

require 'config.php';
session_start();

$email = trim(filter_input(INPUT_POST, 'email'));
$senha = trim(filter_input(INPUT_POST, 'senha'));

$sql = $pdo->prepare("
    SELECT idfuncionario, emailfuncionario, senha
    FROM funcionario
    WHERE emailfuncionario = :email
");

$sql->bindValue(':email', $email);
$sql->execute();

$user = $sql->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($senha, $user['senha'])) {

    $_SESSION['user_id'] = $user['idfuncionario'];
    $_SESSION['user_nome'] = $user['nomefuncionario'];

    echo "
    <script>
        alert('Login realizado com sucesso!');
        window.location.href='../funcionarios/pagina-inicial-func.php';
    </script>
    ";

} else {

    echo "
    <script>
        alert('Atenção! O email e/ou a senha estão incorretos');
        window.history.back();
    </script>
    ";

}

exit;