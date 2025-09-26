<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $name       = $_POST['customer_name'];
    $phone      = $_POST['phone'];
    $address    = $_POST['address'];
    $quantity   = $_POST['quantity'];
    $delivery   = $_POST['delivery'];

    $sql = "INSERT INTO orders (product_id, customer_name, phone, address, quantity, delivery, status) 
            VALUES ('$product_id', '$name', '$phone', '$address', '$quantity', '$delivery', 'Pending')";
    if ($conn->query($sql)) {
        header("Location: cart.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
