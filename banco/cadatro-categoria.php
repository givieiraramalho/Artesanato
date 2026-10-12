<?php

require 'config.php';

$nome = filter_input(INPUT_POST, 'namecategoria');

//Inserindo as informações no banco de dados
$sql = $pdo->prepare("INSERT INTO categorias (nome_categoria) VALUES (:namecategoria)");
$sql-> bindValue(':namecategoria', $nome);

// Executando a inserção
// Caso o cadastro seja feito da maneira correta, irá exibir este alert falando que o cadastro foi realizado com sucesso
if ($sql->execute()) {

    // Verifica quem está logado para redirecionar corretamente
    if ($_SESSION['tipo_usuario'] == 'admin') {
        $destino = '../admin/pagina-inicial-adm.php';
    } else {
        $destino = '../funcionarios/pagina-inicial-func.php';
    }

    echo "
    <script>
        alert('Cadastro da categoria realizado com sucesso!');
        window.location.href = '$destino';
    </script>
    ";

    exit;
    
} else {

    echo "
    <script>
        alert('Erro ao realizar o cadastro da categoria!');
        window.history.back();
    </script>
    ";

    exit;
}

?>