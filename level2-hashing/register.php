<?php
include 'db.php';

if(isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    //Hash password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users_auth (username, password)
            VALUES ('$username', '$hashed_password')";

    if($conn->query($sql)) {
        echo "User Registered Successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<h2>Register</h2>
<form method="POST">
    Username: <input type="text" name="username"><br><br>
    Password: <input type="password" name="password"><br><br>
    <button name="register">Register</button>
</form>

<a href="login.php">Go to Login</a>