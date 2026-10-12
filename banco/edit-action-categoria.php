<?php

require 'config.php';
session_start();

$id = filter_input(INPUT_POST, 'id');
$nome = filter_input(INPUT_POST, 'namecategoria');

if($id && $nome) {
    $sql = $pdo->prepare("UPDATE categorias SET nome_categoria = :nome WHERE pk_id_categorias = :id");
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
        alert('Categoria atualizada com sucesso!');
        window.location.href = '$destino';
    </script>
    ";

    exit;
    
} else {

    echo "
    <script>
        alert('Erro ao atualizar a categoria!');
        window.history.back();
    </script>
    ";

    exit;
}