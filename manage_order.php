<?php
include 'database.php';
$message = "";

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id = intval($_POST['order_id']);
    $status = $_POST['status'];

    // Fetch current order info
    $order = $conn->query("SELECT * FROM orders WHERE id = $id")->fetch_assoc();

    // Deduct stock if status changes to Completed
    if($status === 'Completed' && $order['status'] !== 'Completed') {
        $conn->query("UPDATE products 
                      SET stock = stock - {$order['quantity']} 
                      WHERE id = {$order['product_id']} AND track_stock = 1");
    }

    // Update order status
    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    $stmt->close();

    $message = "Order status updated successfully!";
}

// Handle delete
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $conn->query("DELETE FROM orders WHERE id = $delete_id");
    $message = "Order deleted successfully!";
}

// Filters
$where = "1=1";

// Search by customer
if (!empty($_GET['search'])) {
    $search = "%" . $conn->real_escape_string($_GET['search']) . "%";
    $where .= " AND orders.customer_name LIKE '$search'";
}

// Filter by status
if (!empty($_GET['filter_status']) && $_GET['filter_status'] != "All") {
    $filter_status = $conn->real_escape_string($_GET['filter_status']);
    $where .= " AND orders.status='$filter_status'";
}

// Fetch orders with product info
$sql = "SELECT orders.id, products.name AS product_name, orders.customer_name, orders.phone, orders.address, 
        orders.quantity, orders.status, orders.created_at, orders.product_id 
        FROM orders 
        JOIN products ON orders.product_id = products.id
        WHERE $where
        ORDER BY orders.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Orders</title>
  <style>
    body { font-family: Arial, sans-serif; background: #fafafa; }
    .container { width: 95%; margin: 30px auto; background: white; padding: 20px; border-radius: 8px; }
    h1 { text-align: center; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
    th { background: #333; color: white; }
    select, input[type="text"] { padding: 5px; }
    .filter-form { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
    .filter-form div { display: flex; gap: 10px; align-items: center; }
    .filter-form button { padding: 6px 12px; cursor: pointer; }

    /* Status colors */
    .status { font-weight: bold; padding: 4px 8px; border-radius: 5px; }
    .Pending { background: orange; color: white; }
    .Approved { background: green; color: white; }
    .Rejected { background: red; color: white; }
    .Completed { background: blue; color: white; }

    /* Home button */
    .home-btn {
      display: inline-block;
      padding: 8px 15px;
      margin-bottom: 15px;
      background: brown;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
    }
    .home-btn:hover { background: #7b2f2f; }

    /* Delete button */
    .delete-btn {
      background: red; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;
    }
    .delete-btn:hover { opacity: 0.8; }
  </style>
</head>
<body>
  <div class="container">
    <!-- Home Button -->
    <a href="home.php" class="home-btn">🏠 Home</a>

    <h1>📦 Manage Orders</h1>

    <?php if($message) echo "<div class='status Completed'>$message</div>"; ?>

    <!-- Filter Form -->
    <form method="GET" class="filter-form">
      <div>
        <label>Status:</label>
        <select name="filter_status">
          <option value="All">All</option>
          <option value="Pending" <?= (($_GET['filter_status'] ?? '')=='Pending')?'selected':'' ?>>Pending</option>
          <option value="Approved" <?= (($_GET['filter_status'] ?? '')=='Approved')?'selected':'' ?>>Approved</option>
          <option value="Rejected" <?= (($_GET['filter_status'] ?? '')=='Rejected')?'selected':'' ?>>Rejected</option>
          <option value="Completed" <?= (($_GET['filter_status'] ?? '')=='Completed')?'selected':'' ?>>Completed</option>
        </select>
      </div>

      <div>
        <label>Search:</label>
        <input type="text" name="search" placeholder="Customer name..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
      </div>

      <div>
        <button type="submit">Apply</button>
        <a href="manage_orders.php" style="text-decoration:none;">
          <button type="button">Reset</button>
        </a>
      </div>
    </form>

    <!-- Orders Table -->
    <table>
      <tr>
        <th>Customer</th>
        <th>Product</th>
        <th>Qty</th>
        <th>Phone</th>
        <th>Address</th>
        <th>Status</th>
        <th>Action</th>
        <th>Date</th>
      </tr>
      <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['customer_name']) ?></td>
        <td><?= htmlspecialchars($row['product_name']) ?></td>
        <td><?= $row['quantity'] ?></td>
        <td><?= htmlspecialchars($row['phone']) ?></td>
        <td><?= htmlspecialchars($row['address']) ?></td>
        <td><span class="status <?= $row['status'] ?>"><?= $row['status'] ?></span></td>
        <td>
          <form method="POST" style="display:inline;">
            <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
            <select name="status">
              <option <?= $row['status']=='Pending'?'selected':'' ?>>Pending</option>
              <option <?= $row['status']=='Approved'?'selected':'' ?>>Approved</option>
              <option <?= $row['status']=='Rejected'?'selected':'' ?>>Rejected</option>
              <option <?= $row['status']=='Completed'?'selected':'' ?>>Completed</option>
            </select>
            <button type="submit" name="update_status">Update</button>
          </form>

          <!-- Delete button only for Completed orders -->
          <?php if($row['status'] === 'Completed'): ?>
            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this completed order?');">
              <button class="delete-btn">Delete</button>
            </a>
          <?php endif; ?>
        </td>
        <td><?= $row['created_at'] ?></td>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>
</body>
</html>
