<?php

require 'config.php';

$nome = filter_input(INPUT_POST, 'namepagamento');

//Inserindo as informações no banco de dados
$sql = $pdo->prepare("INSERT INTO pagamento (nome_pagamento) VALUES (:namepagamento)");
$sql-> bindValue(':namepagamento', $nome);

// Executando a inserção
//caso o cadastro seja feito da maneira correta, irá exibir este alert falando que o cadastro foi realizado com sucesso
if ($sql->execute()) {

    echo "
    <script>
        alert('Cadastro da forma de pagamento realizado com sucesso!');
        window.location.href = '../admin/pagina-inicial-adm.php';
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