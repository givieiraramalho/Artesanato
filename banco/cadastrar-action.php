<?php

session_start();

require 'config.php';

// Recebendo os dados do formulário
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';
$senhaRepetida = $_POST['repetir_senha'] ?? '';

// Verificando campos vazios
if (empty($nome) || empty($email) || empty($senha) || empty($senhaRepetida)) {

    echo "
    <script>
        alert('Preencha todos os campos.');
        window.history.back();
    </script>
    ";

    exit;
}

// Verificando se as senhas são iguais
if ($senha !== $senhaRepetida) {

    echo "
    <script>
        alert('As senhas digitadas não são iguais!');
        window.history.back();
    </script>
    ";

    exit;
}

// Verificando se o e-mail já existe
$verifica = $pdo->prepare("
    SELECT id
    FROM cadastro
    WHERE email = ?
");

$verifica->execute([$email]);

if ($verifica->rowCount() > 0) {

    echo "
    <script>
        alert('Este e-mail já está cadastrado!');
        window.history.back();
    </script>
    ";

    exit;
}

// Criptografando a senha
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

// Gerando código de 6 dígitos
$codigo = str_pad(
    random_int(0, 999999),
    6,
    '0',
    STR_PAD_LEFT
);

// Validade de 10 minutos
$codigoExpira = date(
    'Y-m-d H:i:s',
    time() + (10 * 60)
);

// Inserindo o usuário
$query = "
INSERT INTO cadastro
(
    nome,
    email,
    senha,
    codigo_verificacao,
    codigo_expira,
    email_verificado
)
VALUES
(
    ?,
    ?,
    ?,
    ?,
    ?,
    0
)
";

$stmt = $pdo->prepare($query);

$stmt->execute([
    $nome,
    $email,
    $senhaHash,
    $codigo,
    $codigoExpira
]);

// Enviando o código por e-mail
$mail = require 'mailer.php';

$mail->setFrom(
    "artesanatos.eloi8@gmail.com",
    "Artesanatos Eloi"
);

$mail->addAddress($email);

$mail->Subject = "Confirmar Cadastro - Artesanatos Eloi";

$mail->Body = "
<h2>Bem-vindo ao Artesanatos Eloi!</h2>

<p>Seu código de confirmação é:</p>

<h1>{$codigo}</h1>

<p>Este código expira em 10 minutos.</p>
";

try {

    $mail->send();

    $_SESSION['email_confirmacao'] = $email;

    header("Location: ../banco/verificar-conta.php");
    exit;

} catch (Exception $e) {

    echo "
    <script>
        alert('Erro ao enviar o e-mail de confirmação.');
        window.history.back();
    </script>
    ";

    exit;
}