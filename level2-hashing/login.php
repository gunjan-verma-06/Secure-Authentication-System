<?php
include 'db.php';
session_start();

if(isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users_auth WHERE username='$username'";
    $result = $conn->query($sql);

    if($result && $result->num_rows > 0) {

        $row = $result->fetch_assoc();

        // 🔐 Verify hashed password
        if(password_verify($password, $row['password'])) {

            $_SESSION['user'] = $username;
            echo "<h3 style='color:green;'>Login Successful!</h3>";

        } else {
            echo "<h3 style='color:red;'>Invalid Credentials</h3>";
        }

    } else {
        echo "<h3 style='color:red;'>Invalid Credentials</h3>";
    }
}
?>

<h2>Login (Secure Version)</h2>
<form method="POST">
    Username: <input type="text" name="username"><br><br>
    Password: <input type="password" name="password"><br><br>
    <button name="login">Login</button>
</form>

<a href="register.php">Go to Register</a>