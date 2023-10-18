<?php
include_once('config.php');

if (isset($_POST['submit'])) {
    $nameUser = $_POST['name'];
    $emailUser = $_POST['email'];
    $phoneUser = $_POST['phone'];
    $mac = $_POST['macProduct'];
    $passwordUser = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Verificar se as senhas coincidem
    if ($passwordUser != $confirmPassword) {
        echo "As senhas não coincidem.";
    } else {
        // Verificar se o MAC existe na tabela 'status_sensors'
        $macCheckQuery = "SELECT id FROM status_sensors WHERE id_equipamento = '$mac'";
        $macCheckResult = mysqli_query($connection, $macCheckQuery);

        if (mysqli_num_rows($macCheckResult) > 0) {
            // Gere um token de confirmação
            $confirmation_token = bin2hex(random_bytes(16));

            // Inserir os dados do usuário na tabela 'cad_usuarios' com a senha
            $hashedPassword = password_hash($passwordUser, PASSWORD_DEFAULT);
            $insertQuery = "INSERT INTO cad_usuarios (nome, login, user_password_hash, created_at, confirmation_token) 
                            VALUES ('$nameUser', '$emailUser', '$hashedPassword', NOW(), '$confirmation_token')";

            if (mysqli_query($connection, $insertQuery)) {
                // Configurar o PHPMailer para enviar o e-mail de confirmação
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
                $mail->addAddress($emailUser);
                $mail->Subject = 'Confirme seu e-mail';
                $confirmLink = "http://www.localhost/4climatize/confirm.php?token=$confirmation_token";
                $mail->Body = "Clique no link a seguir para confirmar seu e-mail: $confirmLink";

                if ($mail->send()) {
                    // E-mail enviado com sucesso
                    echo "Um e-mail de confirmação foi enviado para o seu endereço de e-mail. Verifique sua caixa de entrada.";
                } else {
                    // Erro ao enviar o e-mail
                    echo 'Erro ao enviar o e-mail de confirmação: ' . $mail->ErrorInfo;
                }
            } else {
                echo "Erro ao cadastrar: " . mysqli_error($connection);
            }
        } else {
            echo "MAC não encontrado na base de dados.";
        }
    }
}
?>




<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - 4Climatize</title>
</head>

<body>
    <form action="register.php" method="POST">
        <p>
            <label for="name" id="name">Nome</label>
            <input type="name" id="name" name="name" required>
        </p>


        <p>
            <label for="email" id="email">Email</label>
            <input type="email" id="email" name="email" required>
        </p>

        <p>
            <label for="phone" id="phone">Telefone</label>
            <input type="number" id="phone" name="phone" required>
        </p>

        <p>
            <label for="macProduct" id="macProduct">Mac do Sensor</label>
            <input type="text" id="macProduct" name="macProduct" required>
        </p>
       
        <p>
            <label for="password" id="password">Senha</label>
            <input type="password" id="password" name="password" required>
        </p> 
        
        <p>
            <label for="confirm_password" id="confirm_password">Confirmar Senha</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </p>

        <input type="submit" name="submit">
    </form>
</body>

</html>