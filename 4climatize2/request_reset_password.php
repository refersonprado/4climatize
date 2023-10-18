<?php
session_start();
include_once('config.php');

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    // Verifique se o email existe na tabela de usuários
    $emailCheckQuery = "SELECT email FROM users WHERE email = '$email'";
    $emailCheckResult = mysqli_query($connection, $emailCheckQuery);

    if (mysqli_num_rows($emailCheckResult) > 0) {
        // Gere um token de redefinição de senha
        $token = bin2hex(random_bytes(16));

        // Insira o token na tabela reset_tokens com o email correspondente
        $insertTokenQuery = "INSERT INTO reset_tokens (token, user_email, created_at) VALUES ('$token', '$email', NOW())";
        mysqli_query($connection, $insertTokenQuery);

        // Configurar o PHPMailer para enviar o e-mail
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'email-ssl.com.br';
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';
        $mail->SMTPAuth = true;
        $mail->Username = 'contato@4climatize.com.br';
        $mail->Password = '4climatizeT3st3!';

        $mail->setFrom('contato@4climatize.com.br', '4Climatize');
        $mail->addAddress($email);
        $mail->Subject = 'Redefinição de Senha';
        $resetLink = "http://www.localhost/4climatize/reset_password.php?token=$token";
        $mail->Body = "Clique no link a seguir para redefinir sua senha: $resetLink";

        if ($mail->send()) {
            header('Location: login.php');
        } else {
            echo 'Erro ao enviar o e-mail: ' . $mail->ErrorInfo;
        }
    } else {
        $error_message = "E-mail não encontrado. Verifique seu e-mail.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <!-- Configurações da página -->
</head>
<body>
    <h1>Solicitar Redefinição de Senha</h1>
    <form action="request_reset_password.php" method="POST">
        <p>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </p>
        <input type="submit" name="submit" value="Enviar">
    </form>
    <?php if (isset($error_message)): ?>
        <div class="error-message">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
</body>
</html>
