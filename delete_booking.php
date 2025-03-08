<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: Customer_Login.php");
    exit;
}

// Check if booking ID is provided
if (!isset($_GET['id'])) {
    header("Location: BookingHistory.php");
    exit;
}

// Include database configuration
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

// Retrieve the booking ID from the URL
$booking_id = $_GET['id'];

// Prepare and execute SQL query to delete booking entry
$sql = "DELETE FROM Booking WHERE Booking_Id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing the statement: " . $conn->error);
}

$stmt->bind_param("i", $booking_id);

if (!$stmt->execute()) {
    die("Error executing the statement: " . $stmt->error);
}

// Close statement
$stmt->close();

// Close connection
$conn->close();

// Redirect back to BookingHistory.php
header("Location: BookingHistory.php");
exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deleting Booking</title>
    <style>
        body {
            background-image: url('image25.jpg'); /* Replace 'image25.jpg' with your image path */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
</head>
<body>
    <!-- You can add any additional content here if needed -->
</body>
</html>

