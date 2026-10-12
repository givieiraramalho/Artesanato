<?php

session_start();

require 'config.php';

$email = filter_input(INPUT_POST, 'email');
$senha = filter_input(INPUT_POST, 'senha');

$sql = $pdo->prepare("
    SELECT id, nome, senha
    FROM cadastro
    WHERE email = :email
    LIMIT 1
");

$sql->bindValue(':email', $email);
$sql->execute();

$user = $sql->fetch(PDO::FETCH_ASSOC);

if ($user) {

    if (password_verify($senha, $user['senha'])) {

        // ==========================================
        // DADOS DO USUÁRIO LOGADO
        // ==========================================

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nome'] = $user['nome'];


        // ==========================================
        // CARREGA O CARRINHO DO BANCO
        // ==========================================

        $sql_carrinho = $pdo->prepare("
            SELECT id_produtos, quantidade
            FROM carrinho
            WHERE id_usuario = :id_usuario
        ");

        $sql_carrinho->bindValue(
            ':id_usuario',
            $_SESSION['user_id'],
            PDO::PARAM_INT
        );

        $sql_carrinho->execute();


        // Cria o carrinho da sessão
        $_SESSION['carrinho'] = [];


        // Coloca os produtos do banco na sessão
        while ($item = $sql_carrinho->fetch(PDO::FETCH_ASSOC)) {

            $_SESSION['carrinho'][$item['id_produtos']] =
                $item['quantidade'];
        }

        // Caso o login seja realizado com sucesso, irá aparecer uma mensagem na tela
        echo "
        <script>
            alert('Login realizado com sucesso!');
            window.location.href = '../pagina-inicial/inicio.php';
        </script>
        ";

        exit();
        
    } else {

        // Senha incorreta
        echo "
        <script>
            alert('E-mail ou senha incorretos!');
            window.location.href = '../login/login.php';
        </script>
        ";

        exit();
    }

} else {

    // Usuário não encontrado
    echo "
    <script>
        alert('Usuário não encontrado!');
        window.location.href = '../login/login.php';
    </script>
    ";

    exit();
}
