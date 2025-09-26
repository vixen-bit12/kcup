<?php
include("database.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete image file
    $res = $conn->query("SELECT image FROM products WHERE id=$id");
    $row = $res->fetch_assoc();
    unlink("uploads/".$row['image']);

    // Delete record
    $conn->query("DELETE FROM products WHERE id=$id");
}

header("Location: admin_coffee.php");
?>
