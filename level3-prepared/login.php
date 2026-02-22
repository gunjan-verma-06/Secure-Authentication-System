<?php
include 'db.php';
session_start();

if(isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // 🔐 Prepared statement prevents SQL injection
    $stmt = $conn->prepare("SELECT * FROM users_auth WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        if(password_verify($password, $row['password'])) {

            $_SESSION['user'] = $username;
            echo "<h3 style='color:green;'>Login Successful!</h3>";

        } else {
            echo "<h3 style='color:red;'>Invalid Credentials</h3>";
        }

    } else {
        echo "<h3 style='color:red;'>Invalid Credentials</h3>";
    }

    $stmt->close();
}
?>

<h2>Login (Prepared Statement Version)</h2>
<form method="POST">
    Username: <input type="text" name="username"><br><br>
    Password: <input type="password" name="password"><br><br>
    <button name="login">Login</button>
</form>

<a href="register.php">Go to Register</a>