<?php
session_start();

// Check kung naka-login at may admin role
if (!isset($_SESSION["username"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login_admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>K-Cup Admin Dashboard</title>
    <link rel="stylesheet" href="css/admin.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f5f5f5;
        }
        header {
            background: #6b4226;
            color: #fff;
            padding: 15px;
            text-align: center;
        }

        /* Tile-style navigation */
        .tile-nav {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
            margin: 20px 0;
        }
        .tile-nav a {
            display: block;
            padding: 15px 25px;
            background: #6b4226;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            transition: transform 0.2s, background 0.2s;
        }
        .tile-nav a:hover {
            background: #8b5e3c;
            transform: translateY(-3px);
        }

        .content {
            padding: 20px;
        }
        .card-container {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .card {
            background: white;
            padding: 20px;
            flex: 1 1 250px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.2);
            min-width: 200px;
        }
    </style>
</head>
<body>
    <header>
        <h1>K-Cup Inventory System</h1>
    </header>

    <!-- Tile Nav -->
    <nav class="tile-nav">
        <a href="home.php">🏠 Home</a>
        <a href="manage_user.php">👤 Manage Users</a>
        <a href="manage_order.php">🛒 Manage Orders</a>
        <a href="admin.php">☕ Manage Menu</a>
        <a href="admin_inventory.php">📦 Manage Inventory</a>
        <a href="history_sales_chart.php">📈 History of Sales</a>
        <a href="login_admin.php">🚪 Logout</a>
    </nav>

    <div class="content">
        <h2>Welcome, <?= htmlspecialchars($_SESSION["username"]) ?>!</h2>
        <p>This is your admin dashboard. Use the navigation above to manage your coffee shop system.</p>

        <div class="card-container">
            <div class="card">
                <h3>👤 Users</h3>
                <p>Manage customer accounts</p>
            </div>
            <div class="card">
                <h3>☕ Menu</h3>
                <p>Update coffee items</p>
            </div>
            <div class="card">
                <h3>🛒 Orders</h3>
                <p>Track and manage orders</p>
            </div>
        </div>
    </div>
</body>
</html>

