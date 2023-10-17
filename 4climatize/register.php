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
        // Iniciar a transação
        mysqli_autocommit($connection, false);

        $macCheckQuery = "SELECT id FROM status_sensors WHERE id_equipamento = '$mac'";
        $macCheckResult = mysqli_query($connection, $macCheckQuery);

        if (mysqli_num_rows($macCheckResult) > 0) {
            // Verifique se o e-mail já existe na tabela de usuários
            $emailCheckQuery = "SELECT id FROM users WHERE email = '$emailUser'";
            $emailCheckResult = mysqli_query($connection, $emailCheckQuery);

            // Verifique se o nome de usuário (login) já existe na tabela cad_usuarios
            $nameCheckQuery = "SELECT id FROM cad_usuarios WHERE login = '$emailUser'";
            $nameCheckResult = mysqli_query($connection, $nameCheckQuery);

            if (mysqli_num_rows($emailCheckResult) > 0 || mysqli_num_rows($nameCheckResult) > 0) {
                echo "E-mail ou nome de usuário já estão em uso.";
            } else {
                $hashedPassword = password_hash($passwordUser, PASSWORD_DEFAULT);
                $currentDate = date('Y-m-d H:i:s');

                $insertQuery1 = "INSERT INTO cad_usuarios (nome, login, created_at) 
                                VALUES ('$nameUser', '$emailUser', '$currentDate')";

                $insertQuery2 = "INSERT INTO users (email, encrypted_password, created_at)
                                VALUES ('$emailUser', '$hashedPassword', '$currentDate')";

                $insertQuery3 = "INSERT INTO cad_clientes (nome, telefone, created_at)
                                VALUES ('$nameUser', '$phoneUser', '$currentDate')";

                if (mysqli_query($connection, $insertQuery1) && mysqli_query($connection, $insertQuery2) && mysqli_query($connection, $insertQuery3)) {
                    // Inicie uma sessão e defina a variável de sessão
                    session_start();
                    $_SESSION['email'] = $emailUser; // Defina a variável de sessão com o e-mail do usuário

                    // Redirecione o usuário para a página de início
                    header('Location: home.php');
                } else {
                    // Rollback a transação se algo der errado
                    mysqli_rollback($connection);
                    echo "Erro ao cadastrar: " . mysqli_error($connection);
                }
            }
        } else {
            echo "MAC não encontrado na base de dados.";
        }

        // Restaurar o modo de autocommit
        mysqli_autocommit($connection, true);
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