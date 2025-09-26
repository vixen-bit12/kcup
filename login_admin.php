<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? '');
    $password = trim($_POST["password"] ?? '');

    // Hardcoded admin check
    if (strtolower($username) === 'admin' && $password === 'admin123') {
        $_SESSION["username"] = "admin";
        $_SESSION["role"] = "admin"; // ✅ Important: Set role
        header("Location: home.php");       
        exit();
    }

    echo "<script>alert('Only the admin account can log in here.'); history.back();</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0" />
    <title>Admin Login</title>
    <link rel="stylesheet" href="/se1/css/login_admin.css" />
</head>
<body>
    <div class="login-container">
        <div class="logo-title">
            <img src="/se1/img/logo.jpg" alt="Logo">
            <h2>WELCOME K-Cup Admin</h2>
        </div>

        <form method="POST">
            <label for="username">Username</label>
            <input id="username" type="text" name="username" required />

            <label for="password">Password</label>
            <input id="password" type="password" name="password" required />

            <input type="submit" value="LOGIN">
        </form>
    </div>
</body>
</html>
