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
                $error_message = "Senha ou email incorreto. Tente novamente.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style/login.css">
  <title>Login - 4Climatize</title>
  <style>
    /* Adicione o CSS aqui */

  

    
    .error-message {
      color: red;
      margin: 10px 0;
    }
  </style>
</head>
<body>
  <div class="content">
    <div class="section-img">
    </div>
    
    <form class="form-login" action="login.php" method="POST">
      <img src="./img/logo-climatize.webp" alt="" class="climatize-logo">
      <div class="fields">
        <p class="field campo-email">
          <label for="email" name="email">Email</label>
          <input type="email" name="email" placeholder="Seu e-mail">
        </p>
  
        <p class="field campo-senha">
          <label for="password" name="password">Senha</label>
          <input type="password" name="password" placeholder="Sua senha">
        </p>
  
        <input class='submit-button' type="submit" name="submit" value="Enviar">
      </div>
  
      <div class="links">
        <a href="request_reset_password.php">Esqueceu a senha?</a>
        <a href="register.php">Cadastre-se</a>
      </div>
      <!-- Adicione a mensagem de erro aqui -->
    <?php if (isset($error_message)): ?>
      <div class="error-message">
        <?php echo $error_message; ?>
      </div>
    <?php endif; ?>
    </form>
    
    
  </div>
</body>
</html>
