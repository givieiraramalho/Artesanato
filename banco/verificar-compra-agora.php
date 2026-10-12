<?php

// Iniciando a sessão
session_start();

// Conexão com o banco de dados
require 'config.php';


// ======================================================
// VERIFICA SE O USUÁRIO ESTÁ LOGADO
// ======================================================

if (!isset($_SESSION['user_id'])) {

    header("Location: ../login/login.php");
    exit();

}

$id_usuario = $_SESSION['user_id'];


// ======================================================
// RECEBE O PRODUTO
// ======================================================

$id_produto = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);


// Verifica se o produto é válido

if (!$id_produto) {

    echo "
    <script>
        alert('Produto inválido.');
        window.location.href = '../produtos/produtos.php';
    </script>
    ";

    exit();

}


// ======================================================
// VERIFICA SE O PRODUTO EXISTE
// ======================================================

$sql_produto = $pdo->prepare("
    SELECT *
    FROM produtos
    WHERE pk_id_produtos = :id_produto
    AND status_produto = 1
    LIMIT 1
");

$sql_produto->bindValue(
    ':id_produto',
    $id_produto,
    PDO::PARAM_INT
);

$sql_produto->execute();

$produto = $sql_produto->fetch(PDO::FETCH_ASSOC);


// Se o produto não existir

if (!$produto) {

    echo "
    <script>
        alert('Produto não encontrado.');
        window.location.href = '../produtos/produtos.php';
    </script>
    ";

    exit();

}


// ======================================================
// SALVA O PRODUTO NA SESSÃO
// ======================================================

// Limpa uma compra anterior
unset($_SESSION['produtos_selecionados']);

// Salva o produto que será comprado
$_SESSION['produtos_selecionados'] = [$id_produto];


// ======================================================
// VERIFICA AS INFORMAÇÕES PESSOAIS
// ======================================================

$sql_pessoa = $pdo->prepare("
    SELECT *
    FROM pessoa
    WHERE id_usuario = :id_usuario
    LIMIT 1
");

$sql_pessoa->bindValue(
    ':id_usuario',
    $id_usuario,
    PDO::PARAM_INT
);

$sql_pessoa->execute();

$pessoa = $sql_pessoa->fetch(PDO::FETCH_ASSOC);


// ======================================================
// VERIFICA O ENDEREÇO
// ======================================================

$sql_endereco = $pdo->prepare("
    SELECT *
    FROM endereco
    WHERE id_usuario = :id_usuario
    LIMIT 1
");

$sql_endereco->bindValue(
    ':id_usuario',
    $id_usuario,
    PDO::PARAM_INT
);

$sql_endereco->execute();

$endereco = $sql_endereco->fetch(PDO::FETCH_ASSOC);


// ======================================================
// DECIDE PARA ONDE O USUÁRIO VAI
// ======================================================

// Não possui informações pessoais

if (!$pessoa) {

    header("Location: ../pagina-forms-pessoa/forms-info-pessoais.php");
    exit();

}


// Não possui endereço

if (!$endereco) {

    header("Location: ../pagina-forms-endereco/forms-info-endereco.php");
    exit();

}


// ======================================================
// PESSOA E ENDEREÇO JÁ EXISTEM
// ======================================================

header("Location: ../finalizar-compra/finalizar-compra.php");
exit();

?>
