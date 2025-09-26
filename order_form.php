<?php
// PHP logic muna (halimbawa kumuha ka ng product sa database)
include 'database.php';
$result = $conn->query("SELECT * FROM products WHERE category='Snacks'");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Snacks</title>
</head>
<body>
  <h1>Snacks</h1>
  <?php while($row = $result->fetch_assoc()): ?>
    <div>
      <h3><?= $row['name'] ?></h3>
      <p>₱<?= $row['price'] ?></p>

      <!-- Modal trigger button -->
      <button data-bs-toggle="modal" data-bs-target="#buyModal<?= $row['id'] ?>">Buy Now</button>

      <!-- Modal -->
      <div class="modal fade" id="buyModal<?= $row['id'] ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <form method="POST" action="place_order.php">
              <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
              <label>Name</label>
              <input type="text" name="customer_name" required>
              <label>Phone</label>
              <input type="text" name="phone" required>
              <label>Address</label>
              <textarea name="address" required></textarea>
              <label>Quantity</label>
              <input type="number" name="quantity" value="1" required>
              <label>Delivery</label>
              <select name="delivery">
                <option value="pickup">Pickup</option>
                <option value="delivery">Delivery</option>
              </select>
              <button type="submit" name="place_order">Add to Cart</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
</body>
</html>
