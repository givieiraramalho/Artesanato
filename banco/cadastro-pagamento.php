<?php

require 'config.php';
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['tipo_usuario'])) {
    die("Usuário não autenticado.");
}

// Recebe o nome da forma de pagamento
$nome = filter_input(INPUT_POST, 'namepagamento');

// Insere as informações no banco de dados
$sql = $pdo->prepare("
    INSERT INTO pagamento (nome_pagamento)
    VALUES (:namepagamento)
");

$sql->bindValue(':namepagamento', $nome);

// Executa a inserção
if ($sql->execute()) {

    // Verifica quem está logado para redirecionar corretamente
    if ($_SESSION['tipo_usuario'] == 'admin') {
        $destino = '../admin/pagina-inicial-adm.php';
    } else {
        $destino = '../funcionarios/pagina-inicial-func.php';
    }

    echo "
    <script>
        alert('Cadastro da forma de pagamento realizado com sucesso!');
        window.location.href = '$destino';
    </script>
    ";

    exit;
    
} else {

    echo "
    <script>
        alert('Erro ao realizar o cadastro da forma de pagamento!');
        window.history.back();
    </script>
    ";

    exit;
}
