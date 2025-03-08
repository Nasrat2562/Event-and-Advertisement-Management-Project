<?php
// Check if the booking ID is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: BookingHistory.php"); // Redirect if ID is not provided
    exit;
}

// Retrieve the booking ID from the URL
$booking_id = $_GET['id'];

// Check if the user is logged in
session_start();
if (!isset($_SESSION['customer_id'])) {
    header("Location: Customer_Login.php"); // Redirect if user is not logged in
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

// Fetch the booking details from the database based on the provided ID
$sql = "SELECT * FROM Booking WHERE Booking_Id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the booking exists
if ($result->num_rows > 0) {
    // Fetch the booking data
    $booking = $result->fetch_assoc();
} else {
    // Redirect if booking does not exist
    header("Location: BookingHistory.php");
    exit;
}

// Close the prepared statement
$stmt->close();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $bookingDate = $_POST['bookingDate'];
    $accountNumber = $_POST['Account_number'];
    $paymentMethod = $_POST['paymentMethod'];

    // Update the booking record in the database with the new data
    $update_sql = "UPDATE Booking SET Date=?, Account_number=?, Payment_method=? WHERE Booking_Id=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssi", $bookingDate, $accountNumber, $paymentMethod, $booking_id);
    $update_stmt->execute();
    $update_stmt->close();

    // Redirect to the booking history page after updating
    header("Location: BookingHistory.php");
    exit;
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-image: url('image37.jpeg'); /* Replace 'background-image.jpg' with your image path */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Booking</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $booking_id); ?>">
        <div class="mb-3">
            <label for="bookingDate" class="form-label">Booking Date</label>
            <input type="date" class="form-control" id="bookingDate" name="bookingDate" value="<?php echo $booking['Date']; ?>">
        </div>
        <div class="mb-3">
            <label for="accountNumber" class="form-label">Account Number</label>
            <input type="text" class="form-control" id="accountNumber" name="Account_number" value="<?php echo $booking['Account_number']; ?>">
        </div>
        <div class="mb-3">
            <label for="paymentMethod" class="form-label">Payment Method</label>
            <select class="form-select" id="paymentMethod" name="paymentMethod">
                <option value="credit" <?php echo ($booking['Payment_method'] == 'credit') ? 'selected' : ''; ?>>Credit Card</option>
                <option value="debit" <?php echo ($booking['Payment_method'] == 'debit') ? 'selected' : ''; ?>>Debit Card</option>
                <option value="Bkash" <?php echo ($booking['Payment_method'] == 'Bkash') ? 'selected' : ''; ?>>Bkash</option>
                <option value="Rocket" <?php echo ($booking['Payment_method'] == 'Rocket') ? 'selected' : ''; ?>>Rocket</option>
                <option value="Nagad" <?php echo ($booking['Payment_method'] == 'Nagad') ? 'selected' : ''; ?>>Nagad</option>
            </select>
        </div>
        <input type="submit" class="btn btn-primary" value="Save Changes">
    </form>
</div>
</body>
</html>

