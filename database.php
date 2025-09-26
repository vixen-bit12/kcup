<?php


$host = "localhost:3307";
$username = "root";
$password = ""; // Ilagay password kung meron
$database = "se.db";

// Create connection
$conn =new mysqli($host, $username, $password, $database);

if ($conn->connect_errno) {
    error_log("Database connection failed: (" . $conn->connect_errno . ") " . $conn->connect_error);
    die("Sorry, we are experiencing database issues.");
}
?>
