<?php
session_start();

if (isset($_POST['submit'])) {
    if (empty($_POST['email']) || empty($_POST['password'])) {
        $error_message = "Por favor, preencha todos os campos.";
    } else {
        include_once('config.php');

        $loginUser = $_POST['email'];
        $loginPassword = $_POST['password'];

        $sql = "SELECT * FROM users WHERE email = '$loginUser'";
        $result = $connection->query($sql);

        if ($result->num_rows < 1) {
            $error_message = "Usuário não encontrado. Verifique seu email.";
        } else {
            $user = $result->fetch_assoc();
            $hashedPassword = $user['encrypted_password'];

            if (password_verify($loginPassword, $hashedPassword)) {
                $_SESSION['email'] = $loginUser;
                $_SESSION['password'] = $loginPassword;

                header('Location: home.php');
            } else {
                $error_message = "Senha incorreta. Tente novamente.";
            }
        }
    }
}

// Agora, você pode exibir a mensagem de erro na página, se existir:
if (isset($error_message)) {
    echo $error_message;
}
?>
