<?php

require 'config.php';

$email = $_POST["email"];

//Iniciando a parte de redefinição de senha que, após a verificação do email, iremos
//atualizar a parte dos reset e também criptografar eles
$token = bin2hex(random_bytes(16));

$token_hash = hash("sha256", $token);

$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

$query = "UPDATE cadastro 
          SET reset_senha = ?,
              reset_expira_senha = ?
          WHERE email = ?";

$stmt = $pdo->prepare($query);

$stmt->execute([$token_hash, $expiry, $email]);

if ($stmt->rowCount() > 0) {
    $mail = require 'mailer.php';

    $mail->setFrom("artesanatos.eloi8@gmail.com", "Artesanatos Eloi");
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';

    $mail->Subject = "Redefinir Senha - Artesanatos Eloi";

    $link = "http://localhost/artesanatos-eloi/banco/reset-password.php?token=" . urlencode($token);

    $mail->Body = "
<div style='font-family: Arial, sans-serif; background-color: #fff5e9; padding: 25px; text-align: center;'>

    <div style='background-color: #ffffff; padding: 30px; border-radius: 10px;'>

        <h1 style='color: #ff8f1f;'>Artesanatos Eloi</h1>

        <h2 style='color: #333333;'>Redefinição de senha</h2>

        <p style='color: #555555; font-size: 16px;'>
            Olá!
        </p>

        <p style='color: #555555; font-size: 16px;'>
            Você solicitou a redefinição da senha da sua conta.
        </p>

        <p style='color: #555555;'>
            Clique no botão abaixo para criar uma nova senha.
        </p>

        <a href='$link'
           style='display: inline-block;
                  background-color: #ff8f1f;
                  color: #ffffff;
                  padding: 14px 25px;
                  text-decoration: none;
                  border-radius: 6px;
                  font-weight: bold;'>
            Redefinir minha senha
        </a>

        <p style='color: #777777; font-size: 14px; margin-top: 25px;'>
            Este link é válido por 30 minutos.
        </p>

        <hr style='border: 1px solid #eeeeee;'>

        <p style='color: #888888; font-size: 12px;'>
            Se você não solicitou a redefinição, ignore este e-mail.
        </p>

        <p style='color: #ff8f1f; font-weight: bold;'>
            Artesanatos Eloi
        </p>

    </div>
</div>
";

    $mail->AltBody = "Você solicitou a redefinição de senha. Acesse este link para criar uma nova senha: $link";

    try {
        $mail->send();

        echo "Mensagem enviada! Verifique sua caixa de entrada.";
    } catch (Exception $e) {
        echo "Não foi possível enviar o e-mail. Verifique a configuração do envio.";
    }
}
