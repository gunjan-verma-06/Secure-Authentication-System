<?php

// 🔐 Secure Session Configuration (Must be before session_start)
ini_set('session.cookie_httponly', 1);     // Prevent JS access to cookies
ini_set('session.use_only_cookies', 1);    // Disable URL-based sessions

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

if (isset($_POST['login'])) {

    // 🔐 CSRF Validation
    if (!isset($_POST['csrf_token']) || 
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("<h3 style='color:red;'>CSRF validation failed!</h3>");
    }

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // 🔐 Prepared Statement (Prevents SQL Injection)
    $stmt = $conn->prepare("SELECT * FROM users_auth WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        // 🔐 Password Verification
        if (password_verify($password, $row['password'])) {

            session_regenerate_id(true);  // Regenerate session after login
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

<h2>Login (Level 4 – Session Hardened + CSRF Protected)</h2>

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