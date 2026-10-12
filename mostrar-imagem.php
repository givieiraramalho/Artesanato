<?php

require '../banco/config.php';

$id = filter_input(INPUT_GET, 'id');

$sql = $pdo->prepare(
    "SELECT foto_produto FROM produtos
     WHERE pk_id_produtos = :id"
);

$sql->bindValue(':id', $id);
$sql->execute();

if($sql->rowCount() > 0){

    $produto = $sql->fetch(PDO::FETCH_ASSOC);

    // Detecta automaticamente o tipo da imagem
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $tipo = $finfo->buffer($produto['foto_produto']);

    header("Content-Type: $tipo");

    echo $produto['foto_produto'];

} else {

    echo "Imagem não encontrada";

}