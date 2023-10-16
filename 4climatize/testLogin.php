<?php


if(isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['password'])) {
 
  include_once('config.php');

  $loginUser = $_POST['email'];
  $loginPassword = $_POST['password'];

  $sql = "SELECT * FROM users WHERE email = '$loginUser' and encrypted_password = '$loginPassword'";

  $result = $connection -> query($sql);
  print_r($result);
  print_r($result);

  if(mysqli_num_rows($result) < 1 ) {
    header('Location: login.php');
  }

  else {
    header('Location: home.php');
  }
}
else {

}
?>