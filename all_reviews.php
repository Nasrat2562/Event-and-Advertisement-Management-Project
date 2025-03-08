<?php
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

// SQL query to retrieve review information grouped by venue_id along with customer username and email
$sql = "SELECT v.Vanue_Name, r.Review, r.Rating, u.Username, u.Email, u.ID as Customer_ID
        FROM Reviews r
        LEFT JOIN Venue v ON r.Venue_id = v.Venue_id
        LEFT JOIN Customer_signup u ON r.Customer_id = u.ID
        ORDER BY r.Venue_id";

$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Reviews</title>
    <style>
          body {
            background-image: url('image40.png');
            background-color: #f0f0f0; /* Fallback color in case the background image doesn't load */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }  
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .review {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .review h2 {
            margin-top: 0;
        }
        .review p {
            margin: 10px 0;
        }
        .block-btn {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }
        .block-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>All Reviews</h1>
        <?php
        if ($result->num_rows > 0) {
            // Output review information
            while ($row = $result->fetch_assoc()) {
                echo '<div class="review">';
                echo '<h2>Venue: ' . $row['Vanue_Name'] . '</h2>';
                echo '<p><strong>Review:</strong> ' . $row['Review'] . '</p>';
                echo '<p><strong>Rating:</strong> ' . $row['Rating'] . '</p>';
                echo '<p><strong>Customer:</strong> ' . $row['Username'] . '</p>';
                echo '<p><strong>Email:</strong> ' . $row['Email'] . '</p>';
                // Add block button with onclick event to trigger blockCustomer function
                echo '<button class="block-btn" onclick="blockCustomer(' . $row['Customer_ID'] . ')">Block</button>';
                echo '</div>';
            }
        } else {
            echo "<p>No reviews found.</p>";
        }
        ?>
    </div>

    <script>
        function blockCustomer(customerId) {
            if (confirm('Are you sure you want to block this customer?')) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'block_customer.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        alert('Customer has been blocked.');
                        location.reload();
                    } else {
                        alert('An error occurred while blocking the customer.');
                    }
                };
                xhr.send('customer_id=' + customerId);
            }
        }
    </script>
</body>
</html>




