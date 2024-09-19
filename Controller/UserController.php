<?php
require '../Model/users.php';

class UserController {
  public function registerUser(){
    
    $UserModel = new UserModel();
    return $UserModel->registerUser();
  }

  public function getusers(){
    {
        session_start();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST["email"];
            $password = $_POST["password"];

            $Register = new getusers();
            $result = $Register->getusers($email, $password);

            if (is_array($result)) {
                $_SESSION['username'] = $result['username'];
                header("Location: index.php");
                exit();
            } else {
                echo "<div class='error'>$result</div>";
            }
        }
    }
}
  }

  ?>
