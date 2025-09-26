<?php
include 'database.php';

$data = json_decode(file_get_contents('php://input'), true);
$customer_name = $data['customer_name'];
$cart = $data['cart']; // product_id => quantity

// Check stock
foreach($cart as $product_id => $qty){
    $result = $conn->query("SELECT stock, track_stock FROM products WHERE id = $product_id")->fetch_assoc();
    if($result['track_stock'] && $result['stock'] < $qty){
        echo json_encode(['status'=>'error','message'=>"Not enough stock for product ID $product_id"]);
        exit;
    }
}

// Insert order
$conn->query("INSERT INTO orders (customer_name) VALUES ('$customer_name')");
$order_id = $conn->insert_id;

// Insert items & deduct stock
foreach($cart as $product_id => $qty){
    $conn->query("INSERT INTO order_items (order_id, product_id, quantity) VALUES ($order_id, $product_id, $qty)");
    $conn->query("UPDATE products SET stock = stock - $qty WHERE id = $product_id AND track_stock = 1");
}

echo json_encode(['status'=>'success','order_id'=>$order_id]);
?>
