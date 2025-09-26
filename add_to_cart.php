<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $customer_name = $_POST['customer_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $quantity = $_POST['quantity'];
    $delivery = $_POST['acquire_type'];

    // Default status = Pending
    $status = "Pending";

    $stmt = $conn->prepare("INSERT INTO orders (product_id, customer_name, phone, address, quantity, delivery, status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssiss", $product_id, $customer_name, $phone, $address, $quantity, $delivery, $status);
    $stmt->execute();
    $stmt->close();

    header("Location: cart.php");
    exit;
}
?>
