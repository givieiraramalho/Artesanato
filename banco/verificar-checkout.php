<?php

//Iniciando a sessão
session_start();

require 'config.php';


// VERIFICA SE O USUÁRIO ESTÁ LOGADO
if (!isset($_SESSION['user_id'])) {

    header("Location: ../login/login.php");
    exit();

}

$id_usuario = $_SESSION['user_id'];

// ======================================================
// RECEBE OS PRODUTOS SELECIONADOS
// ======================================================

if (
    !isset($_POST['produtos_selecionados']) ||
    empty($_POST['produtos_selecionados'])
) {

    echo "
    <script>
        alert('Selecione pelo menos um produto para continuar.');
        window.location.href = '../carrinho-compras/carrinho-compras.php';
    </script>
    ";

    exit();

}


// Salva os produtos selecionados na sessão
$_SESSION['produtos_selecionados'] = $_POST['produtos_selecionados'];


// VERIFICA SE EXISTEM PRODUTOS SELECIONADOS
if (
    !isset($_SESSION['produtos_selecionados']) ||
    empty($_SESSION['produtos_selecionados'])
) {

    echo "
    <script>
        alert('Selecione pelo menos um produto para continuar.');
        window.location.href = '../carrinho-compras/carrinho-compras.php';
    </script>
    ";

    exit();

}

// VERIFICA AS INFORMAÇÕES PESSOAIS
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

if (!$pessoa) {

    header("Location: ../pagina-forms-pessoa/forms-info-pessoais.php");
    exit();

}


if (!$endereco) {

    header("Location: ../pagina-forms-endereco/forms-info-endereco.php");
    exit();

}


// ======================================================
// PESSOA E ENDEREÇO JÁ EXISTEM
// ======================================================

header("Location: ../finalizar-compra/finalizar-compra.php");
exit();