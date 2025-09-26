<?php
session_start();
include "database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? '');
    $password = trim($_POST["password"] ?? '');

    if ($username === '' || $password === '') {
        $error = "Please enter username and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM login WHERE username = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                if (password_verify($password, $row['password'])) {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    header("Location: homepage.php");
                    exit();
                } else {
                    $error = "Incorrect username or password.";
                }
            } else {
                $error = "Incorrect username or password.";
            }
            $stmt->close();
        } else {
            $error = "Database error. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title> Login</title>
  <style>
    body {
      margin: 0; 
      height: 100vh;
      display: flex; 
      align-items: center; 
      justify-content: center;
      background: url('img/bgbg.jpg') no-repeat center center fixed;
      background-size: cover;
      font-family: Arial, sans-serif;
    }
    .login-container {
      background: rgba(255, 255, 255, 0.88); /* semi-transparent */
      padding: 40px 35px;
      border-radius: 12px;
      width: 380px;
      box-shadow: 0 6px 25px rgba(0,0,0,0.35);
      text-align: center;
      backdrop-filter: blur(6px);
    }
    .login-container img {
      width: 70px;
      margin-bottom: 10px;
      border-radius: 50%;
    }
    .login-container h2 {
      margin: 0 0 25px 0;
      color: #222;
      font-size: 1.4rem;
      font-weight: 600;
    }
    form {
      display: flex;
      flex-direction: column;
      gap: 18px;
    }
    label {
      text-align: left;
      font-weight: 600;
      font-size: 0.9rem;
      color: #333;
    }
    .input-wrapper {
      position: relative;
    }
    .input-wrapper input {
      width: 100%;
      padding: 12px 40px 12px 12px;
      border: none;
      border-bottom: 2px solid #aaa;
      outline: none;
      font-size: 0.95rem;
      background: transparent;
      transition: 0.2s;
    }
    .input-wrapper input:focus {
      border-bottom: 2px solid #2575fc;
    }
    .toggle-visibility {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 1.1rem;
    }
    button {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 6px;
      background: #0d2842;
      color: #fff;
      font-weight: bold;
      font-size: 1rem;
      cursor: pointer;
      transition: 0.3s;
    }
    button:hover {
      background: #13385d;
    }
    .error-popup {
      background: #f8d7da;
      color: #721c24;
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid #f5c6cb;
      border-radius: 6px;
      text-align: center;
      font-size: 0.9rem;
    }
    .signup {
      margin-top: 10px;
      font-size: 0.9rem;
    }
    .signup a {
      color: #2575fc;
      text-decoration: none;
      font-weight: bold;
    }
    .signup a:hover {
      text-decoration: underline;
    }
    .back-btn {
      display: inline-block;
      margin-top: 15px;
      padding: 10px 20px;
      background: #6c757d;
      color: #fff;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
      transition: 0.3s;
    }
    .back-btn:hover {
      background: #5a6268;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <img src="img/logo.jpg" alt="K-Cup Logo">
    <h2>WELCOME K-Cup</h2>

    <?php if (!empty($error)): ?>
      <div class="error-popup"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <label for="username">Username</label>
      <div class="input-wrapper">
        <input id="username" type="text" name="username" required />
      </div>

      <label for="password">Password</label>
      <div class="input-wrapper">
        <input id="password" type="password" name="password" required />
        <span class="toggle-visibility" onclick="togglePassword()">👁️</span>
      </div>

      <button type="submit">LOGIN</button>

      <div class="signup">
        Don’t have an account? <a href="signup.php">Sign up</a>
      </div>
    </form>

    <a href="homepage.php" class="back-btn">⬅ Back to Homepage</a>
  </div>

  <script>
    function togglePassword() {
      const pw = document.getElementById('password');
      pw.type = pw.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>
</html>
