<?php

require 'config.php';

$id = filter_input(INPUT_POST, 'id');
$nome = filter_input(INPUT_POST, 'nome');
$preco = filter_input(INPUT_POST, 'preco');
$desconto = filter_input(INPUT_POST, 'desconto');
$estoque = filter_input(INPUT_POST, 'estoque');
$status = filter_input(INPUT_POST, 'status');
$descricao = filter_input(INPUT_POST, 'descricao');

// Descrição da imagem é OPCIONAL
$descricaoImagem = filter_input(INPUT_POST, 'descricaoImage');

// Se não foi preenchida, salva como vazio
$descricaoImagem = $descricaoImagem ?? '';


// ========================================
// CATEGORIAS
// ========================================

$categorias = $_POST['categorias'] ?? [];

if (!is_array($categorias)) {
    $categorias = [];
}


// ========================================
// VALIDAÇÃO
// ========================================

// SOMENTE estes campos são obrigatórios:
// id, nome, preço, estoque e descrição do produto

if (
    empty($id) ||
    empty($nome) ||
    empty($preco) ||
    empty($estoque) ||
    trim($descricao ?? '') === ''
) {

    echo "
    <script>
        alert('Preencha todos os campos obrigatórios.');
        window.history.back();
    </script>
    ";

    exit;
}


// ========================================
// ATUALIZAÇÃO
// ========================================

try {

    $pdo->beginTransaction();


    // ========================================
    // FOTO
    // ========================================

    $foto = null;
    $atualizarFoto = false;

    if (
        isset($_FILES['foto']) &&
        $_FILES['foto']['error'] === UPLOAD_ERR_OK
    ) {

        $foto = file_get_contents($_FILES['foto']['tmp_name']);
        $atualizarFoto = true;
    }


    // ========================================
    // ATUALIZA PRODUTO
    // ========================================

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


    // Só altera a foto se uma nova foi enviada
    if ($atualizarFoto) {
        $sql .= ", foto_produto = :foto";
    }


    $sql .= " WHERE pk_id_produtos = :id";


    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':nome', $nome);
    $stmt->bindValue(':preco', $preco);
    $stmt->bindValue(':desconto', $desconto);
    $stmt->bindValue(':estoque', $estoque);
    $stmt->bindValue(':status', $status, PDO::PARAM_INT);
    $stmt->bindValue(':descricao', $descricao);

    // Pode estar vazio, pois é opcional
    $stmt->bindValue(':descricaoImagem', $descricaoImagem);

    $stmt->bindValue(':id', $id, PDO::PARAM_INT);


    if ($atualizarFoto) {
        $stmt->bindValue(':foto', $foto, PDO::PARAM_LOB);
    }


    $stmt->execute();


    // ========================================
    // REMOVE CATEGORIAS ANTIGAS
    // ========================================

    $stmt = $pdo->prepare("
        DELETE FROM produto_categoria
        WHERE id_produto = :id_produto
    ");

    $stmt->execute([
        ':id_produto' => $id
    ]);


    // ========================================
    // INSERE CATEGORIAS NOVAS
    // ========================================

    if (!empty($categorias)) {

        $stmt = $pdo->prepare("
            INSERT INTO produto_categoria
                (id_produto, id_categoria)
            VALUES
                (:id_produto, :id_categoria)
        ");

        foreach ($categorias as $idCategoria) {

            $stmt->execute([
                ':id_produto' => $id,
                ':id_categoria' => $idCategoria
            ]);
        }
    }


    // ========================================
    // CONFIRMA ALTERAÇÕES
    // ========================================

    $pdo->commit();


   // Verifica quem está logado para redirecionar corretamente
    if ($_SESSION['tipo_usuario'] == 'admin') {
        $destino = '../admin/pagina-inicial-adm.php';
    } else {
        $destino = '../funcionarios/pagina-inicial-func.php';
    }

    echo "
    <script>
        alert('Produto atualizado com sucesso!');
        window.location.href = '$destino';
    </script>
    ";

    exit;


} catch (PDOException $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo "
    <script>
        alert('Ocorreu um erro ao atualizar o produto.');
        window.history.back();
    </script>
    ";

    exit;
}
