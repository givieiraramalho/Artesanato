<?php

require 'config.php';

$id = filter_input(INPUT_POST, 'id');
$cpf = filter_input(INPUT_POST, 'cpf');
$dtNasc = filter_input(INPUT_POST, 'dt_nasc');
$nome = filter_input(INPUT_POST, 'nome');
$telefone = filter_input(INPUT_POST, 'telefone');
$email = filter_input(INPUT_POST, 'email');

if ($id && $cpf && $dtNasc && $nome && $telefone && $email) {

    $sql = $pdo->prepare("UPDATE pessoa SET
        cpf = :cpf,
        dt_nasc = :dtNasc,
        nome = :nome,
        telefone = :telefone,
        email = :email
        WHERE id_usuario = :id");

    // Associando os valores aos parâmetros
    $sql->bindValue(':id', $id);
    $sql->bindValue(':cpf', $cpf);
    $sql->bindValue(':dtNasc', $dtNasc);
    $sql->bindValue(':nome', $nome);
    $sql->bindValue(':telefone', $telefone);
    $sql->bindValue(':email', $email);

    // Executando o comando
    $sql->execute();

    echo "
    <script>
        alert('Dados pessoais atualizados com sucesso!');
        window.location.href = '../pagina-usuario/pagina-usuario.php';
    </script>
    ";
    exit;

} else {

    echo "
    <script>
        alert('Erro ao atualizar os dados pessoais!');
        window.history.back();
    </script>
    ";
    exit;
}