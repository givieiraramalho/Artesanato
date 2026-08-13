<?php

require 'config.php';

$id = filter_input(INPUT_POST, 'id');
$nome = filter_input(INPUT_POST, 'namepagamento');

if($id && $nome) {
    $sql = $pdo->prepare("UPDATE pagamento SET nome_pagamento = :nome WHERE pk_id_pagamento = :id");
    $sql->bindValue(':nome', $nome);
    $sql->bindValue(':id', $id);
    $sql->execute();

    echo "
    <script>
        alert('A forma de pagamento foi atualizada com sucesso!');
        window.location.href = '../admin/pagina-inicial-adm.php';
    </script>
    ";
    exit;
}