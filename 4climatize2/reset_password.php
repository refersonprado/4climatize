<?php
session_start();
include_once('config.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verifique se o token é válido e obtenha o email do usuário
    $tokenCheckQuery = "SELECT user_email FROM reset_tokens WHERE token = '$token'";
    $tokenCheckResult = mysqli_query($connection, $tokenCheckQuery);

    if (mysqli_num_rows($tokenCheckResult) > 0) {
        // Token válido, obtenha o email do usuário
        $user = $tokenCheckResult->fetch_assoc();
        $userEmail = $user['user_email'];
    } else {
        $error_message = "Token inválido. Verifique o token e tente novamente.";
    }
} else {
    $error_message = "Token não encontrado na URL.";
}

if (isset($_POST['submit'])) {
    $newPassword = $_POST['new_password'];

    // Atualize a senha no banco de dados usando o email
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $updatePasswordQuery = "UPDATE users SET encrypted_password = '$hashedPassword' WHERE email = '$userEmail'";

    if (mysqli_query($connection, $updatePasswordQuery)) {
        // Remova o token após a redefinição da senha
        $deleteTokenQuery = "DELETE FROM reset_tokens WHERE token = '$token'";
        if (mysqli_query($connection, $deleteTokenQuery)) {
            // Redirecione o usuário para a página de login
            header('Location: login.php');
        } else {
            echo "Erro ao excluir o token: " . mysqli_error($connection);
        }
    } else {
        echo "Erro ao atualizar a senha: " . mysqli_error($connection);
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <!-- Configurações da página -->
</head>
<body>
    <h1>Redefinir Senha</h1>
    <form action="reset_password.php?token=<?php echo $token; ?>" method="POST">
        <p>
            <label for="new_password">Nova Senha</label>
            <input type="password" id="new_password" name="new_password" required>
        </p>
        <input type="submit" name="submit" value="Redefinir Senha">
    </form>
    <?php if (isset($error_message)): ?>
        <div class="error-message">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
</body>
</html>
