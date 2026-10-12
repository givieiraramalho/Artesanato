<?php

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    return;
}

// ID do usuário logado
$id_usuario = $_SESSION['user_id'];

// Verifica se existe carrinho na sessão
if (!isset($_SESSION['carrinho'])) {
    return;
}

// Percorre os produtos do carrinho
foreach ($_SESSION['carrinho'] as $id_produto => $quantidade) {

    // Verifica se o produto já está salvo no banco
    $sql = $pdo->prepare("
        SELECT id_carrinho
        FROM carrinho
        WHERE id_usuario = :id_usuario
        AND id_produto = :id_produto
    ");

    $sql->bindValue(':id_usuario', $id_usuario);
    $sql->bindValue(':id_produto', $id_produto);
    $sql->execute();

    $produto_existente = $sql->fetch(PDO::FETCH_ASSOC);

    if ($produto_existente) {

        // Atualiza a quantidade
        $sql = $pdo->prepare("
            UPDATE carrinho
            SET quantidade = :quantidade
            WHERE id_usuario = :id_usuario
            AND id_produto = :id_produto
        ");

        $sql->bindValue(':quantidade', $quantidade);
        $sql->bindValue(':id_usuario', $id_usuario);
        $sql->bindValue(':id_produto', $id_produto);

        $sql->execute();

    } else {

        // Insere o produto no banco
        $sql = $pdo->prepare("
            INSERT INTO carrinho
            (id_usuario, id_produto, quantidade)
            VALUES
            (:id_usuario, :id_produto, :quantidade)
        ");

        $sql->bindValue(':id_usuario', $id_usuario);
        $sql->bindValue(':id_produto', $id_produto);
        $sql->bindValue(':quantidade', $quantidade);

        $sql->execute();
    }
}