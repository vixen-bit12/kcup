<?php
session_start();
include("database.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? '');
    $email    = trim($_POST["email"] ?? '');
    $password = trim($_POST["password"] ?? '');

    if (!$username || !$email || !$password) {
        $message = "All fields are required.";
    } else {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM login WHERE username=? OR email=?");
        if(!$stmt){
            die("Database error: " . $conn->error);
        }

        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Username or Email already exists!";
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $insert = $conn->prepare("INSERT INTO login (username, email, password, role, created_at) VALUES (?, ?, ?, 'customer', NOW())");
            if(!$insert){
                die("Database error: " . $conn->error);
            }

            $insert->bind_param("sss", $username, $email, $hashedPassword);

            if ($insert->execute()) {
                echo "<script>
                        alert('Account created successfully!');
                        window.location.href = 'login.php'; 
                      </script>";
                exit();
            } else {
                $message = "Database error: " . $insert->error;
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Account</title>
<style>
body { 
  margin:0; height:100vh; display:flex; align-items:center; justify-content:center; 
  background:url('img/bgbg.jpg') no-repeat center center fixed; 
  background-size:cover; font-family:Arial, sans-serif; 
}
.login-container { 
  background: rgba(255, 255, 255, 0.88); /* semi-transparent */
  padding:40px 35px; border-radius:12px; width:380px; 
  box-shadow:0 6px 25px rgba(0,0,0,0.35); text-align:center; 
  backdrop-filter: blur(6px);
}
h2 { 
  margin-bottom:25px; color:#222; font-size:1.6rem; font-weight:600; 
}
form { 
  display:flex; flex-direction:column; gap:18px; 
}
label { 
  text-align:left; font-weight:600; font-size:0.9rem; color:#333; 
}
.input-wrapper { position:relative; }
.input-wrapper input { 
  width:100%; padding:12px 40px 12px 12px; 
  border:none; border-bottom:2px solid #aaa; 
  outline:none; font-size:0.95rem; 
  background:transparent; transition:0.2s; 
}
.input-wrapper input:focus { 
  border-bottom:2px solid #2575fc; 
}
.toggle-visibility { 
  position:absolute; right:12px; top:50%; 
  transform:translateY(-50%); cursor:pointer; font-size:1.1rem; 
}
input[type="submit"] { 
  width:100%; padding:12px; border:none; border-radius:6px; 
  background:#0d2842; color:#fff; font-weight:bold; 
  font-size:1rem; cursor:pointer; transition:0.3s; 
}
input[type="submit"]:hover { background:#13385d; }
.back-to-login { margin-top:12px; font-size:0.9rem; }
.back-to-login a { 
  color:#2575fc; text-decoration:none; font-weight:bold; 
}
.back-to-login a:hover { text-decoration:underline; }
.error-message { 
  background:#f8d7da; color:#842029; padding:10px; 
  border-radius:5px; margin-bottom:10px; font-size:0.9rem; 
}
</style>
</head>
<body>
<div class="login-container">
    <h2>Create Account</h2>
    <?php if($message) echo "<div class='error-message'>$message</div>"; ?>
    <form method="POST">
        <label for="username">Username</label>
        <div class="input-wrapper">
            <input id="username" type="text" name="username" required />
        </div>

        <label for="email">Email</label>
        <div class="input-wrapper">
            <input id="email" type="email" name="email" required />
        </div>

        <label for="password">Password</label>
        <div class="input-wrapper">
            <input id="password" type="password" name="password" required />
            <span class="toggle-visibility" onclick="togglePassword()">👁️</span>
        </div>

        <input type="submit" value="Sign Up" />
        <div class="back-to-login">
            <a href="login.php">← Back to Login</a>
        </div>
    </form>
</div>

<script>
function togglePassword() {
    const pw = document.getElementById('password');
    pw.type = pw.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>
