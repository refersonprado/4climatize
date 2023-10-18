<?php
include_once('config.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $confirmQuery = "SELECT * FROM cad_usuarios WHERE confirmation_token = '$token'";
    $confirmResult = mysqli_query($connection, $confirmQuery);

    if (mysqli_num_rows($confirmResult) > 0) {
        $email = $user['login'];
        $passwordHash = $user['user_password_hash']; // Certifique-se de usar o campo correto

        // Verifique se o usuário já foi movido para a tabela 'users'
        $checkUserQuery = "SELECT id FROM users WHERE email = '$email'";
        $checkUserResult = mysqli_query($connection, $checkUserQuery);

        if (mysqli_num_rows($checkUserResult) == 0) {
            // Se o usuário ainda não existe em 'users', mova os dados
            $moveQuery = "INSERT INTO users (email, encrypted_password, created_at) VALUES ('$email', '$passwordHash', NOW())";
            if (mysqli_query($connection, $moveQuery)) {
                // Remove da tabela 'cad_usuarios'
                $deleteQuery = "DELETE FROM cad_usuarios WHERE login = '$email'";
                mysqli_query($connection, $deleteQuery);

                echo "E-mail confirmado com sucesso. Agora você pode fazer login.";
            } else {
                echo "Erro ao confirmar o e-mail.";
            }
        } else {
            echo "Este e-mail já foi confirmado. Você pode fazer login.";
        }
    } else {
        echo "Token de confirmação inválido. Verifique o link e tente novamente.";
    }
} else {
    echo "Token não encontrado na URL.";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <!-- Configurações da página, se necessário -->
</head>
<body>
    <h1>Confirmação de Email</h1>
    <?php if (isset($error_message)): ?>
        <div class="error-message">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
</body>
</html>
