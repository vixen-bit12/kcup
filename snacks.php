<?php
include 'database.php';
$result = $conn->query("SELECT * FROM products WHERE category='Snacks'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Snacks</title>
  <style>
    body { font-family: 'Poppins', sans-serif; background: #f9f6f1; margin: 0; padding: 20px; color: #3e2723; }
    .navbar { background: brown; padding: 12px 20px; display: flex; justify-content: space-between; align-items: center; }
    .navbar a { color: white; text-decoration: none; font-weight: bold; margin-left: 15px; }
    .page-title { text-align: center; font-size: 2rem; font-weight: bold; margin: 20px 0; }
    .grid-container { display: grid; gap: 20px; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); max-width: 1200px; margin: auto; }
    .product-card { background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 15px; text-align: center; transition: 0.3s; }
    .product-card:hover { transform: translateY(-5px); }
    .product-card img { width: 100%; height: 160px; object-fit: cover; border-radius: 8px; }
    .buy-btn { margin-top: 8px; padding: 8px 15px; background: brown; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
    .buy-btn:hover { background: #7b2f2f; }
    /* Modal */
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); }
    .modal-content { background: #fff; margin: 8% auto; padding: 20px; width: 400px; border-radius: 10px; position: relative; }
    .close { position: absolute; top: 10px; right: 15px; font-size: 22px; font-weight: bold; cursor: pointer; }
    .modal-content input, .modal-content select { width: 100%; padding: 8px; margin: 8px 0; border: 1px solid #ccc; border-radius: 6px; }
    .modal-content button { width: 100%; padding: 10px; background: brown; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; }
    .modal-content button:hover { background: #7b2f2f; }
  </style>
</head>
<body>

  <div class="navbar">
    <div><a href="product.php">← Back to Menu</a></div>
    <div><a href="cart.php">🛒 My Cart</a></div>
  </div>

  <h1 class="page-title">Tasty Snacks</h1>

  <div class="grid-container">
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="product-card">
        <img src="<?= !empty($row['image']) ? $row['image'] : 'img/snack_default.jpg' ?>" alt="<?= $row['name'] ?>">
        <h2><?= $row['name'] ?></h2>
        <p>₱<?= $row['price'] ?></p>
        <button class="buy-btn"
                data-id="<?= $row['id'] ?>"
                data-name="<?= $row['name'] ?>"
                data-price="<?= $row['price'] ?>">
          Buy Now
        </button>
      </div>
    <?php endwhile; ?>
  </div>

  <!-- Modal -->
  <div id="buyModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Order Details</h2>
      <form method="POST" action="add_to_cart.php">
        <input type="hidden" name="product_id" id="product_id">
        <input type="hidden" name="product_name" id="product_name">
        <input type="hidden" name="product_price" id="product_price">

        <label>Full Name</label>
        <input type="text" name="customer_name" required>

        <label>Phone</label>
        <input type="text" name="phone" required>

        <label>Address</label>
        <input type="text" name="address" required>

        <label>Quantity</label>
        <input type="number" name="quantity" min="1" value="1" required>

        <label>Acquire Type</label>
        <select name="acquire_type" required>
          <option value="Pickup">Pickup</option>
          <option value="Delivery">Delivery</option>
        </select>

        <button type="submit">Add to Cart</button>
      </form>
    </div>
  </div>

  <script>
    const modal = document.getElementById("buyModal");
    const closeBtn = document.querySelector(".close");
    const productIdField = document.getElementById("product_id");
    const productNameField = document.getElementById("product_name");
    const productPriceField = document.getElementById("product_price");

    document.querySelectorAll(".buy-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        productIdField.value = btn.dataset.id;
        productNameField.value = btn.dataset.name;
        productPriceField.value = btn.dataset.price;
        modal.style.display = "block";
      });
    });

    closeBtn.onclick = () => modal.style.display = "none";
    window.onclick = e => { if (e.target == modal) modal.style.display = "none"; }
  </script>

</body>
</html>
