<?php
require 'Mysql.php';
class UserModel extends Mysql {


    public function registerUser() {


if (isset($_POST['submit'])) {
        $name = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");

        if ($stmt === false) {
            die("Error preparing the query: " . $this->conn->error);
        }

        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "User successfully inserted!";
        } else {
            echo "Error inserting user: " . $stmt->error;
        }

        $stmt->close();
    }
}


public function getusers($email, $password) {
    
        $conn = $this->conn;
        $email = mysqli_real_escape_string($conn, $email);
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            
            if(!empty($user)){
                if (password_verify($password, $user["password"])) {
                    return $user;
                } else {
                    return 'Invalid password.';
                }
            }else{
                return "No user found with this password.";
            }   
        } else {
            return 'No user found with this email.';
        }
    }
}


?>