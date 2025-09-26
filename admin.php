<?php
include 'database.php';

// Handle Create
if (isset($_POST['add_item'])) {
    $category = $_POST['category'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    $imagePath = "";
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = $targetFile;
        }
    }

    $conn->query("INSERT INTO products (category, name, price, image) 
                  VALUES ('$category', '$name', '$price', '$imagePath')");
}

// Handle Update
if (isset($_POST['update_item'])) {
    $id = $_POST['id'];
    $category = $_POST['category'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    $imageUpdate = "";
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imageUpdate = ", image='$targetFile'";
        }
    }

    $conn->query("UPDATE products 
                  SET category='$category', name='$name', price='$price' $imageUpdate
                  WHERE id=$id");
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM products WHERE id=$id");
}

// Fetch items grouped by category
$categories = ["Drinks", "Coffee", "Snacks"]; // ✅ Meals → Coffee
$menu = [];
foreach ($categories as $cat) {
    $menu[$cat] = $conn->query("SELECT * FROM products WHERE category='$cat'");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Menu</title>
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #f9f9f9;
    margin: 0;
    padding: 0;
}

/* Header */
header {
    background:brown;
    color: white;
    padding: 20px;
    text-align: center;
    position: sticky;
    top: 0;
    z-index: 100;
}
header h1 {
    margin: 0;
}
.back-btn {
    display: inline-block;
    margin-top: 10px;
    padding: 8px 15px;
    background: #fff;
    color: #4a90e2;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
    transition: 0.3s;
}
.back-btn:hover {
    background: #e6f0ff;
}

/* Tabs */
.tabs {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin: 20px auto;
}
.tab {
    padding: 12px 25px;
    background: #ddd;
    cursor: pointer;
    border-radius: 10px 10px 0 0;
    font-weight: bold;
    transition: 0.3s;
}
.tab:hover {
    background: #bbb;
}
.tab.active {
    background: #fff;
    border-bottom: 2px solid #fff;
}

/* Content */
.tab-content {
    display: none;
    background: #fff;
    padding: 25px;
    border: 1px solid #ccc;
    border-radius: 0 0 10px 10px;
    max-width: 1000px;
    margin: 0 auto 30px auto;
}
.tab-content.active {
    display: block;
}

/* Tables */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
table, th, td {
    border: none;
}
th {
    background: brown;
    color: white;
    padding: 12px;
}
td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}
tr:hover {
    background: #f5faff;
}

/* Forms */
form {
    margin-top: 15px;
}
input[type="text"], 
input[type="number"],
input[type="file"] {
    padding: 8px;
    margin: 3px;
    border: 1px solid #ccc;
    border-radius: 6px;
}
button {
    padding: 8px 14px;
    background: brown;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    transition: 0.3s;
}
button:hover {
    background: #7b2f2f;
}
a.delete-link {
    color: #e74c3c;
    font-weight: bold;
    margin-left: 8px;
    text-decoration: none;
}
a.delete-link:hover {
    text-decoration: underline;
}
img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 6px;
}
</style>
<script>
function showTab(tabName) {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    document.getElementById(tabName + "-tab").classList.add('active');
    document.getElementById(tabName).classList.add('active');
}
</script>
</head>
<body>

<header>
    <h1>🍴 Manage Menu</h1>
    <a href="home.php" class="back-btn">← Back to Homepage</a>
</header>

<div class="tabs">
    <div class="tab active" id="Drinks-tab" onclick="showTab('Drinks')">🥤 Drinks</div>
    <div class="tab" id="Coffee-tab" onclick="showTab('Coffee')">☕ Coffee</div> <!-- ✅ Meals → Coffee -->
    <div class="tab" id="Snacks-tab" onclick="showTab('Snacks')">🍟 Snacks</div>
</div>

<?php foreach ($categories as $cat): ?>
<div class="tab-content <?= $cat == "Drinks" ? "active" : "" ?>" id="<?= $cat ?>">
    <h2><?= $cat ?> Management</h2>

    <!-- Add Item Form -->
    <h3>Add New <?= $cat ?> Item</h3>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="category" value="<?= $cat ?>">
        <input type="text" name="name" placeholder="Item Name" required>
        <input type="number" step="0.01" name="price" placeholder="Price" required>
        <input type="file" name="image" accept="image/*">
        <button type="submit" name="add_item">Add</button>
    </form>

    <!-- List of Items -->
    <h3 style="margin-top:25px;">Existing <?= $cat ?> Items</h3>
    <table>
        <tr>
            <th>ID</th><th>Image</th><th>Name</th><th>Price</th><th>Actions</th>
        </tr>
        <?php while ($row = $menu[$cat]->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td>
                <?php if ($row['image']): ?>
                    <img src="<?= $row['image'] ?>" alt="<?= $row['name'] ?>">
                <?php else: ?>
                    <span>No image</span>
                <?php endif; ?>
            </td>
            <td><?= $row['name'] ?></td>
            <td>₱<?= $row['price'] ?></td>
            <td>
                <!-- Inline Edit Form -->
                <form method="POST" enctype="multipart/form-data" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="hidden" name="category" value="<?= $cat ?>">
                    <input type="text" name="name" value="<?= $row['name'] ?>">
                    <input type="number" step="0.01" name="price" value="<?= $row['price'] ?>">
                    <input type="file" name="image" accept="image/*">
                    <button type="submit" name="update_item">Update</button>
                </form>
                <a href="?delete=<?= $row['id'] ?>" class="delete-link" onclick="return confirm('Delete this item?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php endforeach; ?>

</body>
</html>
