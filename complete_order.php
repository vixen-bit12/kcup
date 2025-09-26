<?php
include 'database.php';

if(!isset($_GET['order_id'])){
    header("Location: manage_orders.php?msg=Invalid+order");
    exit;
}

$order_id = intval($_GET['order_id']);

// Fetch order items
$items = $conn->query("SELECT * FROM order_items WHERE order_id = $order_id");

while($item = $items->fetch_assoc()){
    // Deduct stock for each product if track_stock = 1
    $conn->query("UPDATE products 
                 SET stock = stock - {$item['quantity']} 
                 WHERE id = {$item['product_id']} AND track_stock = 1");
}

// Update order status to completed
$conn->query("UPDATE orders SET status='completed' WHERE id = $order_id");

header("Location: manage_orders.php?msg=Order+$order_id+completed+and+stock+updated");
exit;
?>
