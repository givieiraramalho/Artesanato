<?php

//Fazendo conexão com o banco de dados
require 'config.php';

// Recebendo os dados do formulário
$nome = filter_input(INPUT_POST, 'nome');
$dtnascimento = filter_input(INPUT_POST, 'dtnasc');
$cpf = filter_input(INPUT_POST, 'cpf');
$email = filter_input(INPUT_POST, 'email');
$telefone = filter_input(INPUT_POST, 'telefone');

//Realizando o Select
$sql = $pdo->prepare("INSERT INTO pessoa (cpf, dt_nasc, nome, telefone, email)
 VALUES (:cpf, :dtnasc, :nome, :telefone, :email)");
$sql->bindValue(':cpf', $cpf);
$sql->bindValue(':dtnasc', $dtnascimento);
$sql->bindValue(':nome', $nome);
$sql->bindValue(':telefone', $telefone);
$sql->bindValue(':email', $email);


// Executando a inserção
//caso o cadastro seja feito da maneira correta, irá exibir este alert falando que o cadastro foi realizado com sucesso
if ($sql->execute()) {

    echo "
    <script>
        alert('Cadastro realizado com sucesso!');
        window.location.href = '../pagina-forms-endereco/forms-info-endereco.php';
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
