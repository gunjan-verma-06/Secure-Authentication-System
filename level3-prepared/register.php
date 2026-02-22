<?php
include 'db.php';
session_start();

if(isset($_POST['register'])) {

    // Get user input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Basic validation
    if(empty($username) || empty($password)) {
        echo "<h3 style='color:red;'>All fields are required!</h3>";
    } else {

        // 🔐 Hash password securely (bcrypt)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 🔐 Prepared statement prevents SQL injection
        $stmt = $conn->prepare("INSERT INTO users_auth (username, password) VALUES (?, ?)");

        if($stmt) {

            $stmt->bind_param("ss", $username, $hashed_password);

            if($stmt->execute()) {
                echo "<h3 style='color:green;'>User Registered Successfully!</h3>";
            } else {
                echo "<h3 style='color:red;'>Error: Username may already exist.</h3>";
            }

            $stmt->close();

        } else {
            echo "<h3 style='color:red;'>Database Error!</h3>";
        }
    }
}
?>

<h2>Register (Secure Version - SQL Injection Protected)</h2>

<form method="POST">
    Username: <input type="text" name="username"><br><br>
    Password: <input type="password" name="password"><br><br>
    <button name="register">Register</button>
</form>

<br>
<a href="login.php">Go to Login</a>