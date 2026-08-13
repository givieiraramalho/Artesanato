<?php

require 'config.php';

$nome = filter_input(INPUT_POST, 'nome');
$email = filter_input(INPUT_POST, 'email');
$senha = filter_input(INPUT_POST, 'senha');
$senhaRepetida = filter_input(INPUT_POST, 'repetir_senha');

//Verifica se as senha são iguais
if ($senha !== $senhaRepetida) {

    echo "
    <script>
        alert('As senhas digitadas não são iguais!');
        window.history.back();
    </script>
    ";

    exit;
}

//Criptografia da senha
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

//Verificando se este email já foi cadastrado anteriormente
$verifica = $pdo->prepare("SELECT id FROM cadastro WHERE email = :email");
$verifica->bindValue(':email', $email);
$verifica->execute();

if ($verifica->rowCount() > 0) {

    echo "
    <script>
        alert('Este e-mail já está cadastrado!');
        window.history.back();
    </script>
    ";

    exit;
}

//Inserindo as informações no banco de dados
$sql = $pdo->prepare("INSERT INTO cadastro (nome, email, senha) VALUES (:nome, :email, :senha)");
$sql-> bindValue(':nome', $nome);
$sql-> bindValue(':email', $email);
$sql -> bindValue(':senha', $senhaHash);

//Executando a função
$sql->execute();

//Mandando uma mensagem de que o cadastro foi realizado com sucesso
$_SESSION['sucesso_cadastro'] = "Cadastro realizado com sucesso!";

//retornando para uma outra tela
header("Location: ../login/login.php"); 
exit;