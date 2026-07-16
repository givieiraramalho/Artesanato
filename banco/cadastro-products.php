<?php

require 'config.php';

// Recebendo os dados do formulário
$nome = filter_input(INPUT_POST, 'nome');
$preco = filter_input(INPUT_POST, 'preco');
$desconto = filter_input(INPUT_POST, 'desconto');
$status = filter_input(INPUT_POST, 'status');
$estoque = filter_input(INPUT_POST, 'estoque');
$descricao = filter_input(INPUT_POST, 'descricao');
$descricaoImage = filter_input(INPUT_POST, 'descricaoImage');

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

    echo "
    <script>
        alert('Cadastro do produto realizado com sucesso!');
        window.location.href = 'http://localhost/artesanatos-eloi/admin/pagina-inicial-adm.php';
    </script>
    ";

    exit;

//caso de algum erro no cadastro, ele irá mostrar este outro alert
} else {

    echo "
    <script>
        alert('Erro ao realizar o cadastro!');
        window.history.back();
    </script>
    ";

    exit;
}