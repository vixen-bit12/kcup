<?php
include 'database.php';
$message = "";

// Add Product
if(isset($_POST['add_product'])){
    $name = $_POST['name'];
    $price = $_POST['price'];
    $track_stock = isset($_POST['track_stock']) ? 1 : 0;
    $stock = $track_stock ? $_POST['stock'] : 0;
    
    $conn->query("INSERT INTO products (name, price, track_stock, stock) VALUES ('$name','$price','$track_stock','$stock')");
    $message = "Product added successfully!";
}

// Update Product
if(isset($_POST['update_product'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $track_stock = isset($_POST['track_stock']) ? 1 : 0;
    $stock = $track_stock ? $_POST['stock'] : 0;

    $conn->query("UPDATE products SET name='$name', price='$price', track_stock='$track_stock', stock='$stock' WHERE id='$id'");
    $message = "Product updated successfully!";
}

// Delete Product
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM products WHERE id='$id'");
    $message = "Product deleted successfully!";
}

// Fetch products
$products = $conn->query("SELECT * FROM products ORDER BY name ASC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Inventory</title>
<style>
body { font-family: Arial; background: #f5f5f5; margin: 0; padding: 0; }
.container { width: 90%; margin: 30px auto; background: #fff; padding: 20px; border-radius: 8px; }
.header { display: flex; justify-content: space-between; align-items: center; }
.header h1 { margin: 0; }
.home-btn {
  padding: 8px 15px;
  background: brown;
  color: white;
  text-decoration: none;
  border-radius: 5px;
  font-weight: bold;
}
.home-btn:hover { background: #7b2f2f; }

/* Buttons */
button, .btn { padding: 6px 12px; margin: 2px; border: none; border-radius: 4px; cursor: pointer; }
.add-btn { background: green; color: white; }
.edit-btn { background: orange; color: white; }
.delete-btn { background: red; color: white; }
.add-btn:hover, .edit-btn:hover, .delete-btn:hover { opacity: 0.8; }

/* Table */
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
th { background: #333; color: white; }

/* Stock alerts */
.low-stock { background: #ffcccc; }
.message { padding: 10px; background: #d4edda; color: #155724; margin-bottom: 15px; border-radius: 5px; }
form { margin-bottom: 20px; }
input { padding: 6px; margin: 0 5px; }
label { margin-right: 10px; }
</style>
</head>
<body>
<div class="container">
  <div class="header">
    <h1>📦 Admin Inventory</h1>
    <a href="home.php" class="home-btn">🏠 Home</a>
  </div>

  <?php if($message) echo "<div class='message'>$message</div>"; ?>

  <!-- Add / Edit Product Form -->
  <form method="post">
    <input type="hidden" name="id" id="product_id">
    Name: <input type="text" name="name" id="name" required>
    Price: <input type="number" step="0.01" name="price" id="price" required>
    <label>
      <input type="checkbox" name="track_stock" id="track_stock" onclick="toggleStock()"> Track Stock
    </label>
    Stock: <input type="number" name="stock" id="stock" min="0" value="0" disabled>
    <button type="submit" name="add_product" id="add_btn" class="add-btn">Add Product</button>
    <button type="submit" name="update_product" id="update_btn" class="edit-btn" style="display:none;">Update Product</button>
    <button type="button" onclick="resetForm()">Cancel</button>
  </form>

  <!-- Products Table -->
  <table>
    <tr>
      <th>Name</th>
      <th>Price</th>
      <th>Track Stock</th>
      <th>Stock</th>
      <th>Actions</th>
    </tr>
    <?php while($row = $products->fetch_assoc()): ?>
    <tr class="<?= ($row['track_stock'] && $row['stock'] < 5) ? 'low-stock' : '' ?>">
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td><?= $row['price'] ?></td>
      <td><?= $row['track_stock'] ? 'Yes' : 'No' ?></td>
      <td><?= $row['track_stock'] ? $row['stock'] : '—' ?></td>
      <td>
        <button class="edit-btn" onclick='editProduct(<?= json_encode($row) ?>)'>Edit</button>
        <a href="?delete=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Delete this product?')">Delete</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>

<script>
function toggleStock(){
    document.getElementById('stock').disabled = !document.getElementById('track_stock').checked;
}

function editProduct(product){
    document.getElementById('product_id').value = product.id;
    document.getElementById('name').value = product.name;
    document.getElementById('price').value = product.price;
    document.getElementById('track_stock').checked = product.track_stock == 1;
    document.getElementById('stock').value = product.stock;
    toggleStock();

    document.getElementById('add_btn').style.display = 'none';
    document.getElementById('update_btn').style.display = 'inline-block';
}

function resetForm(){
    document.getElementById('product_id').value = '';
    document.getElementById('name').value = '';
    document.getElementById('price').value = '';
    document.getElementById('track_stock').checked = false;
    document.getElementById('stock').value = 0;
    toggleStock();
    document.getElementById('add_btn').style.display = 'inline-block';
    document.getElementById('update_btn').style.display = 'none';
}
</script>
</body>
</html>
