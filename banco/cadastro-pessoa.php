<?php

// Inicia a sessão
session_start();

// Faz conexão com o banco de dados
require 'config.php';

// ==========================================
// VERIFICANDO SE O USUÁRIO ESTÁ LOGADO
// ==========================================

if (!isset($_SESSION['user_id'])) {

    echo "
    <script>
        alert('Você precisa estar logado para cadastrar seus dados!');
        window.location.href = '../login/login.php';
    </script>
    ";

    exit;
}

// Pegando o ID do usuário logado
$idUsuario = $_SESSION['user_id'];

// ==========================================
// RECEBENDO OS DADOS DO FORMULÁRIO
// ==========================================

$nome = trim(filter_input(INPUT_POST, 'nome') ?? '');
$dtnascimento = trim(filter_input(INPUT_POST, 'dtnasc') ?? '');
$cpf = trim(filter_input(INPUT_POST, 'cpf') ?? '');
$email = trim(filter_input(INPUT_POST, 'email') ?? '');
$telefone = trim(filter_input(INPUT_POST, 'telefone') ?? '');

// ==========================================
// VERIFICANDO SE OS CAMPOS FORAM PREENCHIDOS
// ==========================================

if (
    empty($nome) ||
    empty($dtnascimento) ||
    empty($cpf) ||
    empty($email) ||
    empty($telefone)
) {

    echo "
    <script>
        alert('Preencha todos os campos!');
        window.history.back();
    </script>
    ";

    exit;
}

// ==========================================
// VALIDANDO A DATA DE NASCIMENTO
// ==========================================

// Converte a data no formato esperado pelo input date
$nascimento = DateTime::createFromFormat(
    '!Y-m-d',
    $dtnascimento
);

// Verifica erros na conversão da data
$errosData = DateTime::getLastErrors();

// Define a data mínima permitida
$dataMinima = new DateTime('1900-01-01');

// Define a data atual
$dataAtual = new DateTime('today');

// Verifica se a data é válida
if (
    $nascimento === false ||

    ($errosData !== false &&
        (
            $errosData['warning_count'] > 0 ||
            $errosData['error_count'] > 0
        )
    ) ||

    $nascimento->format('Y-m-d') !== $dtnascimento ||

    $nascimento < $dataMinima ||

    $nascimento > $dataAtual
) {

    echo "
    <script>
        alert('Informe uma data de nascimento válida, entre 01/01/1900 e a data atual.');
        window.history.back();
    </script>
    ";

    exit;
}

// ==========================================
// VERIFICANDO A IDADE DO CLIENTE
// ==========================================

// Calcula a idade em anos completos
$idade = $nascimento->diff($dataAtual)->y;

// Impede o cadastro de menores de 18 anos
if ($idade < 18) {

    echo "
    <script>
        alert('Você precisa ter pelo menos 18 anos realizar uma compra.');
        window.history.back();
    </script>
    ";

    exit;
}

// ==========================================
// VALIDANDO O E-MAIL
// ==========================================

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    echo "
    <script>
        alert('Digite um e-mail válido!');
        window.history.back();
    </script>
    ";

    exit;
}

// ==========================================
// VERIFICANDO SE JÁ EXISTEM DADOS CADASTRADOS
// ==========================================

$sqlVerifica = $pdo->prepare("
    SELECT id
    FROM pessoa
    WHERE id_usuario = :id_usuario
");

$sqlVerifica->bindValue(':id_usuario', $idUsuario);
$sqlVerifica->execute();

// Se encontrou um cadastro
if ($sqlVerifica->fetch()) {

    echo "
    <script>
        alert('Você já possui informações pessoais cadastradas!');
        window.location.href = '../pagina-forms-endereco/forms-info-endereco.php';
    </script>
    ";

    exit;
}

// ==========================================
// CADASTRANDO AS INFORMAÇÕES PESSOAIS
// ==========================================

$sql = $pdo->prepare("
    INSERT INTO pessoa (
        id_usuario,
        cpf,
        dt_nasc,
        nome,
        telefone,
        email
    )
    VALUES (
        :id_usuario,
        :cpf,
        :dtnasc,
        :nome,
        :telefone,
        :email
    )
");

// Associando os valores aos parâmetros
$sql->bindValue(':id_usuario', $idUsuario);
$sql->bindValue(':cpf', $cpf);
$sql->bindValue(':dtnasc', $dtnascimento);
$sql->bindValue(':nome', $nome);
$sql->bindValue(':telefone', $telefone);
$sql->bindValue(':email', $email);

// ==========================================
// EXECUTANDO O CADASTRO
// ==========================================

if ($sql->execute()) {

    echo "
    <script>
        alert('Informações pessoais cadastradas com sucesso!');
        window.location.href = '../pagina-forms-endereco/forms-info-endereco.php';
    </script>
    ";

    exit;
}

// Caso ocorra algum erro
echo "
<script>
    alert('Erro ao realizar o cadastro!');
    window.history.back();
</script>
";

exit;
