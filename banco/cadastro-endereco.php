<?php

// Iniciando a sessão para conseguir acessar o usuário logado
session_start();

// Fazendo conexão com o banco de dados
require 'config.php';

// Verificando se o usuário está logado
if (!isset($_SESSION['user_id'])) {

    echo "
    <script>
        alert('Você precisa estar logado para cadastrar um endereço!');
        window.location.href = '../login/login.php';
    </script>
    ";

    exit;
}


// Pegando o ID do usuário logado
$idUsuario = $_SESSION['user_id'];

// Recebendo os dados do formulário
$rua = filter_input(INPUT_POST, 'rua');
$bairro = filter_input(INPUT_POST, 'bairro');
$cidade = filter_input(INPUT_POST, 'cidade');
$cep = filter_input(INPUT_POST, 'cep');
$estado = filter_input(INPUT_POST, 'estado');
$numeroCasa = filter_input(INPUT_POST, 'casa');
$numeroApartamento = filter_input(INPUT_POST, 'apartamento');
$referencia = filter_input(INPUT_POST, 'referencia');

// Realizando o cadastro do endereço
$sql = $pdo->prepare("
    INSERT INTO endereco (
        id_usuario,
        cep,
        cidade,
        estado,
        numero_casa,
        nome_rua,
        nome_bairro,
        apartamento,
        referencia
    )
    VALUES (
        :id_usuario,
        :cep,
        :cidade,
        :estado,
        :casa,
        :rua,
        :bairro,
        :apartamento,
        :referencia
    )
");


// Associando os valores aos parâmetros
$sql->bindValue(':id_usuario', $idUsuario);
$sql->bindValue(':cep', $cep);
$sql->bindValue(':cidade', $cidade);
$sql->bindValue(':estado', $estado);
$sql->bindValue(':casa', $numeroCasa);
$sql->bindValue(':rua', $rua);
$sql->bindValue(':bairro', $bairro);
$sql->bindValue(':apartamento', $numeroApartamento);
$sql->bindValue(':referencia', $referencia);


// Executando a inserção
if ($sql->execute()) {

    echo "
    <script>
        alert('Cadastro do endereço realizado com sucesso!');
        window.location.href = '../finalizar-compra/finalizar-compra.php';
    </script>
    ";

    exit;
} else {

    echo "
    <script>
        alert('Erro ao realizar o cadastro!');
        window.history.back();
    </script>
    ";

    exit;
}
