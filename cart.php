<?php
include 'database.php';

// Fetch all orders (example: by customer name or phone kung may login system dapat filter pa)
$sql = "SELECT orders.id, products.name AS product_name, orders.customer_name, orders.phone, orders.address, 
        orders.quantity, orders.status, orders.created_at 
        FROM orders 
        JOIN products ON orders.product_id = products.id
        ORDER BY orders.created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
  <title>My Cart</title>
  <style>
    body { font-family: Arial, sans-serif; background: #fafafa; }
    .container { width: 95%; margin: 30px auto; background: white; padding: 20px; border-radius: 8px; }
    h1 { text-align: center; }

    /* Home Button */
    .home-btn {
      display: inline-block;
      margin-bottom: 20px;
      padding: 10px 20px;
      background: #333;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      transition: 0.3s;
    }
    .home-btn:hover {
      background: #555;
    }

    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
    th { background: #333; color: white; }

    /* Status Colors */
    .status { font-weight: bold; padding: 4px 8px; border-radius: 5px; }
    .Pending { background: orange; color: white; }
    .Approved { background: green; color: white; }
    .Rejected { background: red; color: white; }
    .Completed { background: blue; color: white; }
  </style>
</head>
<body>
  <div class="container">
    <h1>🛒 My Orders</h1>

    <!-- Home Button -->
    <a href="product.php" class="home-btn">🏠 Back</a>

    <table>
      <tr>
        <th>Product</th>
        <th>Qty</th>
        <th>Customer</th>
        <th>Phone</th>
        <th>Address</th>
        <th>Status</th>
        <th>Delivery Status</th>
        <th>Date</th>
      </tr>
      <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['product_name']) ?></td>
        <td><?= $row['quantity'] ?></td>
        <td><?= htmlspecialchars($row['customer_name']) ?></td>
        <td><?= htmlspecialchars($row['phone']) ?></td>
        <td><?= htmlspecialchars($row['address']) ?></td>
        <td><span class="status <?= $row['status'] ?>"><?= $row['status'] ?></span></td>
        <td>
          <?php if ($row['status'] == "Pending"): ?>
            ⏳ Waiting for approval
          <?php elseif ($row['status'] == "Approved"): ?>
            🚚 On the way (For Delivery/Pickup)
          <?php elseif ($row['status'] == "Rejected"): ?>
            ❌ Order Rejected
          <?php elseif ($row['status'] == "Completed"): ?>
            ✅ Delivered / Completed
          <?php endif; ?>
        </td>
        <td><?= $row['created_at'] ?></td>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>
</body>
</html>
