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

//Inserindo as informações no banco de dados
$sql = $pdo->prepare("INSERT INTO cadastro (nome, email, senha) VALUES (:nome, :email, :senha)");
$sql-> bindValue(':nome', $nome);
$sql-> bindValue(':email', $email);
$sql -> bindValue(':senha', $senhaHash);

//Executando a função
$sql->execute();

//retornando para uma outra tela
header("Location: http://localhost/artesanatos-eloi/login/login.php");
exit;