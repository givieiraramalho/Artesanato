<?php

require 'config.php';

$id = filter_input(INPUT_POST, 'id');
$nome = filter_input(INPUT_POST, 'nome');
$preco = filter_input(INPUT_POST, 'preco');
$desconto = filter_input(INPUT_POST, 'desconto');
$estoque = filter_input(INPUT_POST, 'estoque');
$status = filter_input(INPUT_POST, 'status');
$descricao = filter_input(INPUT_POST, 'descricao');
$descricaoImagem = filter_input(INPUT_POST, 'descricaoImage');

if ($id && $nome && $preco && $estoque && $descricao) {

    // Verifica se foi enviada uma nova imagem
    $foto = null;
    $atualizarFoto = false;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = file_get_contents($_FILES['foto']['tmp_name']);
        $atualizarFoto = true;
    }

    $sql = "
        UPDATE produtos
        SET
            nome_produto = :nome,
            preco_produto = :preco,
            desconto = :desconto,
            estoque = :estoque,
            status_produto = :status,
            descricao = :descricao,
            descricao_imagem = :descricaoImagem
    ";

    // Atualiza a foto apenas se uma nova foi enviada
    if ($atualizarFoto) {
        $sql .= ", foto_produto = :foto";
    }

    $sql .= " WHERE pk_id_produtos = :id";

    $sql = $pdo->prepare($sql);

    $sql->bindValue(':nome', $nome);
    $sql->bindValue(':preco', $preco);
    $sql->bindValue(':desconto', $desconto);
    $sql->bindValue(':estoque', $estoque);
    $sql->bindValue(':status', $status, PDO::PARAM_INT);
    $sql->bindValue(':descricao', $descricao);
    $sql->bindValue(':descricaoImagem', $descricaoImagem);
    $sql->bindValue(':id', $id);

    if ($atualizarFoto) {
        $sql->bindParam(':foto', $foto, PDO::PARAM_LOB);
    }

    $sql->execute();

    echo "
    <script>
        alert('O produto foi atualizado com sucesso!');
        window.location.href = '../admin/pagina-inicial-adm.php';
    </script>
    ";

    exit;
}