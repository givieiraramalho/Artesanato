<?php

require 'config.php';

$id = filter_input(INPUT_POST, 'id');
$nome = filter_input(INPUT_POST, 'namecategoria');

if($id && $nome) {
    $sql = $pdo->prepare("UPDATE categorias SET nome_categoria = :nome WHERE pk_id_categorias = :id");
    $sql->bindValue(':nome', $nome);
    $sql->bindValue(':id', $id);
    $sql->execute();

    echo "
    <script>
        alert('A categoria foi atualizada com sucesso!');
        window.location.href = 'http://localhost/artesanatos-eloi/admin/pagina-inicial-adm.php';
    </script>
    ";
    exit;
}