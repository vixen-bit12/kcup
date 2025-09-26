<?php
include 'database.php';
$result = $conn->query("SELECT * FROM products WHERE category='Coffee'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coffee</title>
  <style>
    body { font-family: 'Poppins', sans-serif; background:#f9f6f1; margin:0; padding:20px; color:#3e2723; }
    .navbar { background:brown; padding:12px 20px; display:flex; justify-content:space-between; align-items:center; color:#fff; }
    .navbar a { color:#fff; text-decoration:none; font-weight:bold; margin-left:12px; }
    .page-title { text-align:center; font-size:2rem; margin:18px 0; }
    .grid-container { display:grid; gap:20px; grid-template-columns:repeat(auto-fill, minmax(250px,1fr)); max-width:1200px; margin:0 auto; }
    .product-card { background:#fff; border-radius:10px; box-shadow:0 4px 8px rgba(0,0,0,0.1); padding:15px; text-align:center; transition:0.2s; }
    .product-card:hover { transform:translateY(-4px); }
    .product-card img { width:100%; height:160px; object-fit:cover; border-radius:8px; }
    .product-card h2 { font-size:1.1rem; margin:10px 0 6px; color:#5a3e2b; }
    .product-card p { font-weight:bold; margin:4px 0; }
    .buy-btn { margin-top:8px; padding:8px 15px; background:brown; color:#fff; border:none; border-radius:6px; cursor:pointer; font-weight:bold; }
    .buy-btn:hover { background:#7b2f2f; }

    /* modal */
    .modal { display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.6); justify-content:center; align-items:center; }
    .modal.open { display:flex; }
    .modal-box { background:#fff; width:420px; max-width:95%; padding:18px; border-radius:10px; position:relative; }
    .modal-box .close { position:absolute; right:12px; top:10px; cursor:pointer; font-size:20px; font-weight:bold; }
    .modal-box input, .modal-box select, .modal-box textarea { width:100%; padding:8px; margin:8px 0; border:1px solid #ccc; border-radius:6px; box-sizing:border-box; }
    .modal-box button { width:100%; padding:10px; background:brown; color:#fff; border:none; border-radius:6px; cursor:pointer; font-weight:bold; }
    .modal-box button:hover { background:#7b2f2f; }
  </style>
</head>
<body>

  <div class="navbar">
    <div><a href="product.php">← Back to Menu</a></div>
    <div><a href="cart.php">🛒 My Cart</a></div>
  </div>

  <h1 class="page-title">Coffee Selection</h1>

  <div class="grid-container">
    <?php while ($row = $result->fetch_assoc()): ?>
      <?php
        $pid = (int)$row['id'];
        $pname = htmlspecialchars($row['name'], ENT_QUOTES);
        $pprice = $row['price'];
        $pimg = !empty($row['image']) ? htmlspecialchars($row['image'], ENT_QUOTES) : 'img/coffee_default.jpg';
      ?>
      <div class="product-card">
        <img src="<?= $pimg ?>" alt="<?= $pname ?>">
        <h2><?= $pname ?></h2>
        <p>₱<?= number_format($pprice,2) ?></p>
        <button class="buy-btn"
                data-id="<?= $pid ?>"
                data-name="<?= $pname ?>"
                data-price="<?= $pprice ?>">
          Buy Now
        </button>
      </div>
    <?php endwhile; ?>
  </div>

  <!-- Modal -->
  <div id="buyModal" class="modal" aria-hidden="true">
    <div class="modal-box">
      <span class="close" id="modalClose">&times;</span>
      <h2 id="modalTitle">Order</h2>

      <form method="POST" action="add_to_cart.php">
        <input type="hidden" name="product_id" id="product_id">
        <input type="hidden" name="product_name" id="product_name">
        <input type="hidden" name="product_price" id="product_price">

        <label>Full Name</label>
        <input type="text" name="customer_name" required>

        <label>Phone</label>
        <input type="text" name="phone" required>

        <label>Address</label>
        <textarea name="address" rows="2" required></textarea>

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
  const modal = document.getElementById('buyModal');
  const modalClose = document.getElementById('modalClose');
  const productIdField = document.getElementById('product_id');
  const productNameField = document.getElementById('product_name');
  const productPriceField = document.getElementById('product_price');
  const modalTitle = document.getElementById('modalTitle');

  document.querySelectorAll('.buy-btn').forEach(btn => {
    btn.addEventListener('click', function(){
      productIdField.value = this.dataset.id;
      productNameField.value = this.dataset.name;
      productPriceField.value = this.dataset.price;
      modalTitle.textContent = "Order: " + this.dataset.name + " — ₱" + parseFloat(this.dataset.price).toFixed(2);
      modal.classList.add('open');
    });
  });

  modalClose.addEventListener('click', () => modal.classList.remove('open'));
  window.addEventListener('click', e => { if (e.target === modal) modal.classList.remove('open'); });
</script>

</body>
</html>
