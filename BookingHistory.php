<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: Customer_Login.php");
    exit;
}

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

// Retrieve the current customer's ID
$customer_id = $_SESSION['customer_id'];

// Prepare and execute SQL query to select booking data for the current customer
$sql = "SELECT * FROM Booking WHERE Customer_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

$booking_data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Check if booking date is less than current date
        $booking_date = strtotime($row['Date']);
        $current_date = strtotime(date('Y-m-d'));
        if ($booking_date < $current_date) {
            $row['Description'] = "Time passed";
        } else {
            $row['Description'] = "Coming";
        }
        $booking_data[] = $row;
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
          body {
            background-image: url('image10.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px; /* Increased container width */
            margin-top: 50px;
        }
        .table th,
        .table td {
            white-space: nowrap; /* Prevent wrapping of text */
        }
        .table td {
            overflow: hidden; /* Hide overflow text */
            text-overflow: ellipsis; /* Show ellipsis for overflow text */
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">Booking History</h2>
    <?php if (!empty($booking_data)): ?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Customer ID</th>
                    <th scope="col">Booking ID</th>
                    <th scope="col">Event ID</th>
                    <th scope="col">Booking Date</th>
                    <th scope="col">Payment Method</th>
                    <th scope="col">Account Number</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th> <!-- New column for Edit and Delete buttons -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($booking_data as $booking): ?>
                    <tr>
                        <td><?php echo $booking['Customer_ID']; ?></td>
                        <td><?php echo $booking['Booking_Id']; ?></td>
                        <td><?php echo $booking['Venue_id']; ?></td>
                        <td><?php echo $booking['Date']; ?></td>
                        <td><?php echo $booking['Payment_method']; ?></td>
                        <td><?php echo $booking['Account_number']; ?></td>
                        <td><?php echo $booking['Description']; ?></td>
                        <td>
                            <!-- Edit button -->
                            <a href="edit_booking.php?id=<?php echo $booking['Booking_Id']; ?>" class="btn btn-primary">Edit</a>
                            <!-- Delete button -->
                            <a href="delete_booking.php?id=<?php echo $booking['Booking_Id']; ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center">No booking history available.</p>
    <?php endif; ?>
</div>

</body>
</html>








