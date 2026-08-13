<?php

require 'config.php';

$id = filter_input(INPUT_POST, 'id');
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

// Verifica se o usuário deseja alterar a senha
if (!empty($senha)) {

    if ($senha !== $senhaRepetida) {

        echo "
        <script>
            alert('As senhas digitadas não são iguais!');
            history.back();
        </script>";
        exit;
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
}

if ($id && $nome && $email && $cpf && $telefone && $dtnascimento && $cargo) {

    $sql = "
        UPDATE funcionario
        SET
            nomefuncionario = :nome,
            emailfuncionario = :email,
            cpffuncionario = :cpf,
            telefonefuncionario = :telefone,
            datanascimento = :dtnascimento,
            cargofuncionario = :cargo,
            statusfuncionario = :status,
            nomecontato = :nomeContato,
            telefonecontato = :telContato
    ";

    $senhaHash = null;

    // Atualiza a senha somente se ela foi informada
    if (!empty($senha)) {
        $sql .= ", senha = :senha";
    }

    $sql .= " WHERE idfuncionario = :id";

    $sql = $pdo->prepare($sql);

    $sql->bindValue(':nome', $nome);
    $sql->bindValue(':email', $email);
    $sql->bindValue(':cpf', $cpf);
    $sql->bindValue(':telefone', $telefone);
    $sql->bindValue(':dtnascimento', $dtnascimento);
    $sql->bindValue(':cargo', $cargo);
    $sql->bindValue(':status', $status);
    $sql->bindValue(':nomeContato', $nomeContato);
    $sql->bindValue(':telContato', $telContato);
    $sql->bindValue(':id', $id);

    // Faz o bind da senha apenas se ela foi alterada
    if (!empty($senha)) {
        $sql->bindValue(':senha', $senhaHash);
    }

    $sql->execute();

    echo "
    <script>
        alert('O funcionário foi atualizado com sucesso!');
        window.location.href = '../admin/pagina-inicial-adm.php';
    </script>
    ";
    exit;
}