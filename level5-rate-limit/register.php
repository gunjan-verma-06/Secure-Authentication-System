<?php

// 🔐 Secure Session Configuration (Must come before session_start)
ini_set('session.cookie_httponly', 1);     // Prevent JS from accessing session cookie
ini_set('session.use_only_cookies', 1);    // Disable session ID in URL

session_start();

// 🔐 Prevent Session Fixation
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

// 🔐 Generate CSRF Token if not already set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include 'db.php';

if (isset($_POST['register'])) {

    // 🔐 CSRF Validation
    if (!isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("<h3 style='color:red;'>CSRF validation failed!</h3>");
    }

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Basic Validation
    if (empty($username) || empty($password)) {
        echo "<h3 style='color:red;'>All fields are required!</h3>";
    } else {

        // 🔐 Hash Password Securely (bcrypt)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 🔐 Prepared Statement (Prevents SQL Injection)
        $stmt = $conn->prepare("INSERT INTO users_auth (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {

            session_regenerate_id(true);  // Regenerate session after registration
            echo "<h3 style='color:green;'>User Registered Successfully!</h3>";

        } else {
            echo "<h3 style='color:red;'>Error: Username may already exist.</h3>";
        }

        $stmt->close();
    }
}
?>

<h2>Register (Level 4 – Session Hardened + CSRF Protected)</h2>

<form method="POST">

    Username:
    <input type="text" name="username" required>
    <br><br>

    Password:
    <input type="password" name="password" required>
    <br><br>

    <!-- 🔐 CSRF Hidden Token -->
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

    <button name="register">Register</button>

</form>

<br>
<a href="login.php">Go to Login</a>