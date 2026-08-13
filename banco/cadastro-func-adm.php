<?php

require 'config.php';

$nome = filter_input(INPUT_POST, 'nome');
$email = filter_input(INPUT_POST, 'email');
$cpf = filter_input(INPUT_POST, 'cpf');
$telefone = filter_input(INPUT_POST, 'telefone');
$dtnascimento = filter_input(INPUT_POST, 'dtnasc');
$cargo = filter_input(INPUT_POST, 'cargo');
$status = filter_input(INPUT_POST, 'status');
$senha = filter_input(INPUT_POST, 'senha');
$senhaRepetida = filter_input(INPUT_POST, 'repetir_senha');
$nomeContato = filter_input(INPUT_POST, 'nomeEmergencia');
$telContato = filter_input(INPUT_POST, 'telEmergencia');

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
$sql = $pdo->prepare("INSERT INTO funcionario (nomefuncionario, emailfuncionario, cpffuncionario, telefonefuncionario, datanascimento, cargofuncionario, 
statusfuncionario, senha, nomecontato, telefonecontato)
 VALUES (:nome, :email, :cpf, :telefone, :dtnasc, :cargo, :status, :senha, :nomeEmergencia, :telEmergencia)");
$sql->bindValue(':nome', $nome);
$sql->bindValue(':email', $email);
$sql->bindValue(':cpf', $cpf);
$sql->bindValue(':telefone', $telefone);
$sql->bindValue(':dtnasc', $dtnascimento);
$sql->bindValue(':cargo', $cargo);
$sql->bindValue(':status', $status, PDO::PARAM_INT);
$sql->bindValue(':senha', $senhaHash);
$sql->bindValue(':nomeEmergencia', $nomeContato);
$sql->bindValue(':telEmergencia', $telContato);

// Executando a inserção
//caso o cadastro seja feito da maneira correta, irá exibir este alert falando que o cadastro foi realizado com sucesso
if ($sql->execute()) {

    echo "
    <script>
        alert('Cadastro do Funcionário realizado com sucesso!');
        window.location.href = '../admin/pagina-inicial-adm.php';
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
