<?php

require 'config.php';
session_start();

// Recebe os dados do formulário
$email = trim(filter_input(INPUT_POST, 'email'));
$senha = trim(filter_input(INPUT_POST, 'senha'));

// Busca o funcionário pelo e-mail
$sql = $pdo->prepare("
    SELECT 
        idfuncionario,
        nomefuncionario,
        emailfuncionario,
        senha
    FROM funcionario
    WHERE emailfuncionario = :email
");

$sql->bindValue(':email', $email);
$sql->execute();

$user = $sql->fetch(PDO::FETCH_ASSOC);

// Verifica se o usuário existe e se a senha está correta
if ($user && password_verify($senha, $user['senha'])) {

    // Cria as sessões do funcionário
    $_SESSION['user_id'] = $user['idfuncionario'];
    $_SESSION['user_nome'] = $user['nomefuncionario'];

    // Identifica o tipo de usuário logado
    $_SESSION['tipo_usuario'] = 'funcionario';

    echo "
    <script>
        alert('Login realizado com sucesso!');
        window.location.href='../funcionarios/pagina-inicial-func.php';
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
