<?php

require 'config.php';

$nome = filter_input(INPUT_POST, 'namecategoria');

//Inserindo as informações no banco de dados
$sql = $pdo->prepare("INSERT INTO categorias (nome_categoria) VALUES (:namecategoria)");
$sql-> bindValue(':namecategoria', $nome);

// Executando a inserção
//caso o cadastro seja feito da maneira correta, irá exibir este alert falando que o cadastro foi realizado com sucesso
if ($sql->execute()) {

    echo "
    <script>
        alert('Cadastro da categoria realizado com sucesso!');
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

?>