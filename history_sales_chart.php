<?php
session_start();
include 'database.php';

// Check admin login
if (!isset($_SESSION["username"]) || $_SESSION["username"] !== "admin") {
    header("Location: login_admin.php");
    exit();
}

// Handle deletion of a completed order
if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM orders WHERE id=$id AND status='Completed'");
}

// Date filters
$where = "o.status='Completed'";
$from = $_GET['from_date'] ?? '';
$to   = $_GET['to_date'] ?? '';

if($from != '') $where .= " AND DATE(o.created_at) >= '$from'";
if($to != '') $where .= " AND DATE(o.created_at) <= '$to'";

// Fetch completed orders
$sql = "SELECT o.id, p.name AS product_name, o.customer_name, o.quantity, p.price, 
               (o.quantity * p.price) AS total_price, o.created_at
        FROM orders o
        JOIN products p ON o.product_id = p.id
        WHERE $where
        ORDER BY o.created_at DESC";
$result = $conn->query($sql);

// Total sales
$sql_total = "SELECT SUM(o.quantity * p.price) AS total_sales
              FROM orders o
              JOIN products p ON o.product_id = p.id
              WHERE $where";
$total_sales = $conn->query($sql_total)->fetch_assoc()['total_sales'] ?? 0;

// Prepare daily sales for chart
$daily_sales_result = $conn->query("
    SELECT DATE(o.created_at) AS sale_date, SUM(o.quantity * p.price) AS daily_total
    FROM orders o
    JOIN products p ON o.product_id = p.id
    WHERE o.status='Completed'
    GROUP BY DATE(o.created_at)
    ORDER BY sale_date ASC
");
$dates = [];
$totals = [];
while($row = $daily_sales_result->fetch_assoc()){
    $dates[] = $row['sale_date'];
    $totals[] = $row['daily_total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>History of Sales</title>
<style>
    body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
    .container { width: 95%; margin: 30px auto; background: white; padding: 20px; border-radius: 10px; }
    h1 { text-align: center; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
    th { background: #6b4226; color: white; }
    .home-btn { display: inline-block; padding: 8px 15px; margin-bottom: 15px; background: #6b4226; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
    .home-btn:hover { background: #8b5e3c; }
    .filter-form { margin-bottom: 15px; display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
    .filter-form label { font-weight: bold; }
    .filter-form input[type="date"] { padding: 5px; }
    .filter-form button { padding: 5px 10px; cursor: pointer; }
    .total-sales { font-weight: bold; margin-bottom: 10px; font-size: 1.1rem; }
    .delete-btn { padding:4px 8px; background:red; color:white; border:none; border-radius:4px; cursor:pointer; }
</style>
</head>
<body>
<div class="container">
    <a href="home.php" class="home-btn">🏠 Back to Dashboard</a>
    <h1>📈 History of Sales</h1>

    <!-- Date filter form -->
    <form method="GET" class="filter-form">
        <label>From: <input type="date" name="from_date" value="<?= htmlspecialchars($from) ?>"></label>
        <label>To: <input type="date" name="to_date" value="<?= htmlspecialchars($to) ?>"></label>
        <button type="submit">Filter</button>
        <a href="history_sales.php"><button type="button">Reset</button></a>
    </form>

    <!-- Display total sales -->
    <p class="total-sales">Total Sales: ₱<?= number_format($total_sales,2) ?></p>

    <!-- Orders table -->
    <table>
        <tr>
            <th>Order ID</th>
            <th>Product</th>
            <th>Customer</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php if($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= htmlspecialchars($row['customer_name']) ?></td>
                <td><?= $row['quantity'] ?></td>
                <td>₱<?= number_format($row['price'],2) ?></td>
                <td>₱<?= number_format($row['total_price'],2) ?></td>
                <td><?= $row['created_at'] ?></td>
                <td>
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this order?');" class="delete-btn">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">No sales found for the selected period.</td>
            </tr>
        <?php endif; ?>
    </table>

    <!-- Chart.js graph -->
    <canvas id="salesChart" style="max-width:100%; height:300px; margin-top:30px;"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($dates) ?>,
        datasets: [{
            label: 'Daily Sales',
            data: <?= json_encode($totals) ?>,
            backgroundColor: 'rgba(107,66,38,0.6)',
            borderColor: 'rgba(107,66,38,1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
</body>
</html>
