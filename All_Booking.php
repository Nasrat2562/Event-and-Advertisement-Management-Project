<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all booking details from the Booking table ordered by date in ascending order
$sql = "SELECT Booking.*, Customer_signup.Email FROM Booking LEFT JOIN Customer_signup ON Booking.Customer_ID = Customer_signup.ID ORDER BY Booking.Date ASC";
$result = $conn->query($sql);

$highlightedId = isset($_GET['id']) ? intval($_GET['id']) : null;

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Bookings</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold; /* Make the font bold */
            font-size: 16px;
        }
        .chat-btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .chat-btn:hover {
            background-color: #0056b3;
        }
        .highlight {
            background-color: yellow;
        }
    </style>
</head>
<body>
    <h2>All Bookings</h2>
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Venue ID</th>
                <th>Payment Method</th>
                <th>Date</th>
                <th>Account Number</th>
                <th>Customer ID</th>
                <th>Email</th>
                <th>Description</th>
                <th>Chat</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr id="booking-<?php echo $row['Booking_Id']; ?>" class="<?php if ($highlightedId == $row['Booking_Id']) echo 'highlight'; ?>">
                        <td><?php echo $row['Booking_Id']; ?></td>
                        <td><?php echo $row['Venue_id']; ?></td>
                        <td><?php echo $row['Payment_method']; ?></td>
                        <td><?php echo $row['Date']; ?></td>
                        <td><?php echo $row['Account_number']; ?></td>
                        <td><?php echo $row['Customer_ID']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><?php echo $row['Description']; ?></td>
                        <td><button class="chat-btn" onclick="openGmail('<?php echo $row['Email']; ?>')">Chat</button></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No bookings available</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <script>
        function openGmail(email) {
            window.location.href = 'mailto:' + email;
        }

        document.addEventListener("DOMContentLoaded", function() {
            var highlightedId = <?php echo json_encode($highlightedId); ?>;
            if (highlightedId) {
                var element = document.getElementById('booking-' + highlightedId);
                if (element) {
                    element.scrollIntoView();
                }
            }
        });
    </script>
</body>
</html>


