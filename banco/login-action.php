<?php

require 'config.php';
session_start();

// pega dados do formulário
$email = filter_input(INPUT_POST, 'email');
$senha = filter_input(INPUT_POST, 'senha');

// busca usuário pelo email
$sql = $pdo->prepare("SELECT id, senha FROM cadastro WHERE email = :email");
$sql->bindValue(':email', $email);
$sql->execute();

$user = $sql->fetch();

//Nesta parte, ele irá validar o login e irá verificar se está correto
if ($user && password_verify($senha, $user['senha'])) {

    // cria uma sessão do id
    $_SESSION['user_id'] = $user['id'];

    // Caso o email e a senha estejam corretos, ele será redirecionado para a página inicial
    header("Location: http://localhost/artesanatos-eloi/pagina-inicial/inicio.php");
    exit;

} else {

    // Caso alguma informação estiver errada, irá aparecer este alert na tela
    echo "
    <script>
        alert('Atenção! O email e/ou a senha estão incorretos');
        window.history.back();
    </script>
    ";

    exit;
}

?>