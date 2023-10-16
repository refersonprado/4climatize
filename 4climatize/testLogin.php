<?php
session_start();

if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['password'])) {
    include_once('config.php');

    $loginUser = $_POST['email'];
    $loginPassword = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$loginUser'";
    $result = $connection->query($sql);

    if ($result->num_rows < 1) {
        header('Location: login.php');
    } else {
        $user = $result->fetch_assoc();
        $hashedPassword = $user['encrypted_password'];

        if (password_verify($loginPassword, $hashedPassword)) {
            $_SESSION['email'] = $loginUser;
            $_SESSION['password'] = $loginPassword;

            header('Location: home.php');
        } else {
            unset($_SESSION['email']);
            unset($_SESSION['password']);
            header('Location: login.php');
        }
    }
} else {
}
?>
