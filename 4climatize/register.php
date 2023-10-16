<?php
include_once('config.php');

if (isset($_POST['submit'])) {
    $nameUser = $_POST['name'];
    $emailUser = $_POST['email'];
    $phoneUser = $_POST['phone'];
    $mac = $_POST['macProduct'];
    $passwordUser = $_POST['password'];

    $macCheckQuery = "SELECT id FROM status_sensors WHERE id_equipamento = '$mac'";
    $macCheckResult = mysqli_query($connection, $macCheckQuery);

    if (mysqli_num_rows($macCheckResult) > 0) {
        $hashedPassword = password_hash($passwordUser, PASSWORD_DEFAULT);

        $currentDate = date('Y-m-d H:i:s');

        $insertQuery1 = "INSERT INTO cad_usuarios (nome, login, created_at) 
                        VALUES ('$nameUser', '$emailUser', '$currentDate')";

        if (mysqli_query($connection, $insertQuery1)) {
            $insertQuery2 = "INSERT INTO users (email, encrypted_password, created_at)
                            VALUES ('$emailUser', '$hashedPassword', '$currentDate')";

            if (mysqli_query($connection, $insertQuery2)) {
                $insertQuery3 = "INSERT INTO cad_clientes (nome, telefone, created_at)
                                VALUES ('$nameUser', '$phoneUser', '$currentDate')";

                if (mysqli_query($connection, $insertQuery3)) {
                    header('Location: home.php');
                } else {
                    echo "Erro ao cadastrar o cliente: " . mysqli_error($connection);
                }
            } else {
                echo "Erro ao cadastrar o usuário: " . mysqli_error($connection);
            }
        } else {
            echo "Erro ao cadastrar o cliente: " . mysqli_error($connection);
        }
    } else {
        echo "MAC não encontrado na base de dados.";
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

        <input type="submit" name="submit">
    </form>
</body>

</html>