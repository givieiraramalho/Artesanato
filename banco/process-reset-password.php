<?php

require 'config.php';

$token = $_POST["token"];

$token_hash = hash("sha256", $token);

$query = "SELECT * FROM cadastro
          WHERE reset_senha = ?";

$stmt = $pdo->prepare($query);

$stmt->execute([$token_hash]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user === null) {
    die("Token não encontrado");
}

if(strtotime($user["reset_expira_senha"]) <= time()) {
    die("O token expirou!");
}

$senha = filter_input(INPUT_POST, 'senha');
$senhaRepetida = filter_input(INPUT_POST, 'confirmacao_senha');

//Verifica se as senha são iguais
if ($senha !== $senhaRepetida) {

    echo "
    <script>
        alert('As senhas digitadas não são iguais!');
        window.history.back();
    </script>
    ";

    exit;
}

//Criptografia da senha
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

$query = "UPDATE cadastro
          SET senha = ?,
          reset_senha = NULL,
          reset_expira_senha = NULL
          WHERE id = ?";

$stmt = $pdo->prepare($query);

$stmt->execute([$senhaHash, $user["id"]]);

    echo "
    <script>
        alert('Senha atualizada com sucesso!');
        window.location.href = '../login/login.php';
    </script>
    ";

?>