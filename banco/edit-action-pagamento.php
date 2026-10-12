<?php

require 'config.php';
session_start();

$id = filter_input(INPUT_POST, 'id');
$nome = filter_input(INPUT_POST, 'namepagamento');

if($id && $nome) {
    $sql = $pdo->prepare("UPDATE pagamento SET nome_pagamento = :nome WHERE pk_id_pagamento = :id");
    $sql->bindValue(':nome', $nome);
    $sql->bindValue(':id', $id);
    $sql->execute();

    // Verifica quem está logado para redirecionar corretamente
    if ($_SESSION['tipo_usuario'] == 'admin') {
        $destino = '../admin/pagina-inicial-adm.php';
    } else {
        $destino = '../funcionarios/pagina-inicial-func.php';
    }

    echo "
    <script>
        alert('Forma de Pagamento atualizada com sucesso!');
        window.location.href = '$destino';
    </script>
    ";

    exit;
    
} else {

    echo "
    <script>
        alert('Erro ao atualizar a forma de pagamento!');
        window.history.back();
    </script>
    ";

    exit;
}