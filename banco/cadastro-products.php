<?php

require 'config.php';

session_start();

// Recebendo os dados do formulário
$nome = filter_input(INPUT_POST, 'nome');
$preco = filter_input(INPUT_POST, 'preco');
$desconto = filter_input(INPUT_POST, 'desconto');
$status = filter_input(INPUT_POST, 'status');
$estoque = filter_input(INPUT_POST, 'estoque');
$descricao = filter_input(INPUT_POST, 'descricao');
$descricaoImage = filter_input(INPUT_POST, 'descricaoImage');
$categorias = $_POST['categorias'] ?? [];

// Recebendo a imagem
$foto = null;

if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    // Lê o conteúdo da imagem
    $foto = file_get_contents($_FILES['foto']['tmp_name']);
}

// Inserindo no banco
$sql = $pdo->prepare("
    INSERT INTO produtos
    (nome_produto, preco_produto, descricao, foto_produto, descricao_imagem, estoque, desconto, status_produto)
    VALUES
    (:nome, :preco, :descricao, :foto, :descricaoImage, :estoque, :desconto, :status)
");

$sql->bindValue(':nome', $nome);
$sql->bindValue(':preco', $preco);
$sql->bindValue(':descricao', $descricao);
$sql->bindParam(':foto', $foto, PDO::PARAM_LOB);
$sql->bindValue(':descricaoImage', $descricaoImage);
$sql->bindValue(':estoque', $estoque);
$sql->bindValue(':desconto', $desconto);
$sql->bindValue(':status', $status, PDO::PARAM_INT);

// Executando a inserção
//caso o cadastro seja feito da maneira correta, irá exibir este alert falando que o cadastro foi realizado com sucesso
if ($sql->execute()) {

    $idProduto = $pdo->lastInsertId();

    if (!empty($_POST['categorias'])) {

        $sqlCategoria = $pdo->prepare("
            INSERT INTO produto_categoria
            (id_produto, id_categoria)
            VALUES
            (:produto, :categoria)
        ");

        foreach ($_POST['categorias'] as $idCategoria) {

            if (!$sqlCategoria->execute([
                ':produto' => $idProduto,
                ':categoria' => $idCategoria
            ])) {

                echo "
                <script>
                    alert('Erro ao salvar as categorias do produto!');
                    window.history.back();
                </script>
                ";
                exit;
            }
        }
    }

    //Verfica o tipo de ususário que está cadastrado
    if ($_SESSION['tipo_usuario'] == 'admin') {
        $destino = '../admin/pagina-inicial-adm.php';
    } else {
        $destino = '../funcionarios/pagina-inicial-func.php';
    }
    //Redirecionando para a página conforme o tipo de usuário cadastro
    echo "
    <script>
    alert('Cadastro do produto realizado com sucesso!');
    window.location.href = '$destino';
    </script>
    ";

} else {

    echo "
    <script>
        alert('Erro ao realizar o cadastro do produto!');
        window.history.back();
    </script>
    ";

    exit;
}
