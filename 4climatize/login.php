

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - 4Climatize</title>
  <link rel="stylesheet" href="./style/login.css">
</head>
<body>
  <div class="content">
    <div class="section-img">
    </div>
    
    <form class="form-login" action="testLogin.php" method="POST">
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
        <a href="#">Esqueceu a senha?</a>
        <a href="#">Cadastre-se</a>
      </div>
    </form>
  </div>
</body>
</html>