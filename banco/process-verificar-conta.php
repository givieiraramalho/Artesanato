<?php

session_start();

require 'config.php';

// Verifica se existe um e-mail salvo na sessão
if (!isset($_SESSION['email_confirmacao'])) {

    echo "
    <script>
        alert('Sessão expirada. Faça o cadastro novamente.');
        window.location.href='../cadastro/cadastro.php';
    </script>
    ";

    exit;
}

$email = $_SESSION['email_confirmacao'];

$codigo = trim($_POST['codigo'] ?? '');

// Verifica se o campo foi preenchido
if (empty($codigo)) {

    echo "
    <script>
        alert('Digite o código de verificação.');
        window.history.back();
    </script>
    ";

    exit;
}

// Busca o usuário pelo e-mail e código
$query = "SELECT *
          FROM cadastro
          WHERE email = ?
          AND codigo_verificacao = ?
          AND codigo_expira > NOW()";

$stmt = $pdo->prepare($query);

$stmt->execute([
    $email,
    $codigo
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Se não encontrou usuário
if (!$user) {

    echo "
    <script>
        alert('Código inválido ou expirado!');
        window.history.back();
    </script>
    ";

    exit;
}

// Atualiza o cadastro confirmando o e-mail
$query = "UPDATE cadastro
          SET email_verificado = 1,
              codigo_verificacao = NULL,
              codigo_expira = NULL
          WHERE id = ?";

$stmt = $pdo->prepare($query);

$stmt->execute([
    $user['id']
]);

// Remove a sessão de confirmação
unset($_SESSION['email_confirmacao']);

// Mensagem de sucesso
echo "
<script>
    alert('E-mail confirmado com sucesso!');
    window.location.href='../login/login.php';
</script>
";

exit;