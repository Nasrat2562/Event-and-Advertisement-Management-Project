<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];

    // Database configuration
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "shop_db";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update the customer record to mark as blocked
    $stmt = $conn->prepare("UPDATE Customer_signup SET blocked = 1 WHERE ID = ?");
    $stmt->bind_param("i", $customer_id);

    if ($stmt->execute()) {
        echo 'Customer has been blocked.';
    } else {
        echo 'Error: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
