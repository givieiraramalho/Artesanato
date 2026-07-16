<?php

require 'config.php';

$nome = filter_input(INPUT_POST, 'nome');
$email = filter_input(INPUT_POST, 'email');
$telefone = filter_input(INPUT_POST, 'telefone');
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
$sql = $pdo->prepare("INSERT INTO admin (email, nome, telefone, senha) VALUES (:email, :nome, :telefone, :senha)");
$sql-> bindValue(':email', $email);
$sql-> bindValue(':nome', $nome);
$sql -> bindValue(':telefone', $telefone);
$sql -> bindValue(':senha', $senhaHash);

// Executando a inserção
//caso o cadastro seja feito da maneira correta, irá exibir este alert falando que o cadastro foi realizado com sucesso
if ($sql->execute()) {

    echo "
    <script>
        alert('Cadastro realizado com sucesso!');
        window.location.href = 'http://localhost/artesanatos-eloi/admin/login-adm.php';
    </script>
    ";

    exit;

//caso de algum erro no cadastro, ele irá mostrar este outro alert
} else {

    echo "
    <script>
        alert('Erro ao realizar o cadastro!');
        window.history.back();
    </script>
    ";

    exit;
}