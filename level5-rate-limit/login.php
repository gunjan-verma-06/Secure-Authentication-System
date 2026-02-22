<?php

ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);

session_start();

if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include 'db.php';

if (isset($_POST['login'])) {

    if (!isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("<h3 style='color:red;'>CSRF validation failed!</h3>");
    }

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users_auth WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        // 🔐 Check if account is locked
        if ($row['lock_until'] && strtotime($row['lock_until']) > time()) {
            echo "<h3 style='color:red;'>Account locked. Try again later.</h3>";
            exit();
        }

        if (password_verify($password, $row['password'])) {

            // Reset failed attempts
            $reset = $conn->prepare("UPDATE users_auth SET failed_attempts=0, lock_until=NULL WHERE username=?");
            $reset->bind_param("s", $username);
            $reset->execute();

            session_regenerate_id(true);
            $_SESSION['user'] = $username;

            echo "<h3 style='color:green;'>Login Successful!</h3>";

        } else {

            $failed = $row['failed_attempts'] + 1;

            if ($failed >= 5) {
                $lock_time = date("Y-m-d H:i:s", strtotime("+5 minutes"));
                $update = $conn->prepare("UPDATE users_auth SET failed_attempts=?, lock_until=? WHERE username=?");
                $update->bind_param("iss", $failed, $lock_time, $username);
                $update->execute();

                echo "<h3 style='color:red;'>Too many attempts. Account locked for 5 minutes.</h3>";
            } else {
                $update = $conn->prepare("UPDATE users_auth SET failed_attempts=? WHERE username=?");
                $update->bind_param("is", $failed, $username);
                $update->execute();

                echo "<h3 style='color:red;'>Invalid Credentials</h3>";
            }
        }

    } else {
        echo "<h3 style='color:red;'>Invalid Credentials</h3>";
    }
}
?>



<h2>Login (Level 5 – Fully Hardened Authentication)</h2>

<form method="POST">

    Username:
    <input type="text" name="username" required>
    <br><br>

    Password:
    <input type="password" name="password" required>
    <br><br>

    <!-- 🔐 CSRF Hidden Token -->
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

    <button name="login">Login</button>

</form>

<br>
<a href="register.php">Go to Register</a>






