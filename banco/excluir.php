<?php

require 'config.php';

$tabela = filter_input(INPUT_GET, 'tabela');
$campo   = filter_input(INPUT_GET, 'campo');
$id      = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Tabelas permitidas
$tabelasPermitidas = [
    'funcionario',
    'produtos',
    'categorias',
    'pagamento'
];

// Campos permitidos
$camposPermitidos = [
    'idfuncionario',
    'pk_id_produtos',
    'pk_id_categorias',
    'pk_id_pagamento'
];

if (
    $id &&
    in_array($tabela, $tabelasPermitidas) &&
    in_array($campo, $camposPermitidos)
) {

    $sql = $pdo->prepare("DELETE FROM {$tabela} WHERE {$campo} = :id");
    $sql->bindValue(':id', $id, PDO::PARAM_INT);
    $sql->execute();

    echo "
    <script>
        alert('Registro excluído com sucesso!');
        history.back();
    </script>
    ";

} else {

    echo "
    <script>
        alert('Dados inválidos.');
        history.back();
    </script>
    ";
}