<?php
session_start();
include("database.php");

// Check admin role
if(!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: login_admin.php");
    exit();
}

$message = "";

// Add user
if(isset($_POST['add_user'])){
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = $_POST['role'] ?? 'user';

    if($username && $email && $password){
        $stmt = $conn->prepare("SELECT id FROM login WHERE username=? OR email=?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows > 0){
            $message = "Username or Email already exists!";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO login (username,email,password,role) VALUES (?,?,?,?)");
            $insert->bind_param("ssss",$username,$email,$hashed,$role);
            $insert->execute();
            $insert->close();
            $message = "User added successfully!";
        }
        $stmt->close();
    } else {
        $message = "All fields are required!";
    }
}

// Delete user
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM login WHERE id=$id");
    header("Location: manage_user.php");
    exit();
}

// Fetch users
$users = $conn->query("SELECT id, username, email, role, created_at FROM login ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users</title>
<style>
body{font-family:Arial;background:#f5f5f5;margin:0;padding:0;}
.container{width:90%;margin:30px auto;background:#fff;padding:20px;border-radius:8px;}
h1{text-align:center;margin-bottom:20px;}
table{width:100%;border-collapse:collapse;margin-top:20px;}
th,td{padding:10px;border:1px solid #ddd;text-align:center;}
th{background:#333;color:white;}
button,a.btn{padding:6px 12px;border:none;border-radius:4px;cursor:pointer;text-decoration:none;color:white;}
.delete-btn{background:red;}
.delete-btn:hover{opacity:0.8;}
.home-btn{display:inline-block;padding:8px 15px;margin-bottom:15px;background:brown;color:white;text-decoration:none;border-radius:5px;font-weight:bold;}
.home-btn:hover{background:#7b2f2f;}
.message{padding:10px;background:#d4edda;color:#155724;margin-bottom:15px;border-radius:5px;text-align:center;}
.add-user-form{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:15px;align-items:center;justify-content:flex-start;}
.add-user-form input,.add-user-form select{padding:6px;}
.add-user-form button{background:green;cursor:pointer;}
</style>
</head>
<body>
<div class="container">
<a href="home.php" class="home-btn">🏠 Home</a>
<h1>👥 Manage Users</h1>

<?php if($message) echo "<div class='message'>$message</div>"; ?>

<form method="POST" class="add-user-form">
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <select name="role">
        <option value="user">User</option>
        <option value="admin">Admin</option>
    </select>
    <button type="submit" name="add_user">Add User</button>
</form>

<table>
<tr>
<th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Created At</th><th>Action</th>
</tr>
<?php if($users && $users->num_rows>0): ?>
<?php while($row=$users->fetch_assoc()): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= htmlspecialchars($row['username']) ?></td>
<td><?= htmlspecialchars($row['email']) ?></td>
<td><?= $row['role'] ?></td>
<td><?= $row['created_at'] ?></td>
<td><a href="?delete=<?= $row['id'] ?>" class="btn delete-btn" onclick="return confirm('Delete this user?')">Delete</a></td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr><td colspan="6">No users found</td></tr>
<?php endif; ?>
</table>
</div>
</body>
</html>
