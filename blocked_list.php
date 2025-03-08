<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "shop_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve blocked email addresses
$blocked_emails_sql = "SELECT Email FROM sign_up WHERE status = 0";
$blocked_emails_result = $conn->query($blocked_emails_sql);

// Check if the form is submitted for unblocking
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['unblock_email'])) {
        $unblock_email = $_POST['unblock_email'];
        // Update the status to unblock the email
        $unblock_sql = "UPDATE sign_up SET status = 1 WHERE Email = ?";
        $stmt = $conn->prepare($unblock_sql);
        $stmt->bind_param("s", $unblock_email);
        $stmt->execute();
        $stmt->close();
        // Redirect to prevent form resubmission
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blocked Email Addresses</title>
    <style>
         body {
            background-image: url('image40.png');
            background-color: #f0f0f0; /* Fallback color in case the background image doesn't load */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        </style>
    <!-- Add your CSS link here -->
</head>
<body>
    <div class="container">
        <h1>Blocked Advertiser</h1>
        <?php
        if (mysqli_num_rows($blocked_emails_result) > 0) {
            while ($row = mysqli_fetch_assoc($blocked_emails_result)) {
                echo '<div class="blocked-advertiser">';
                echo '<p><strong>Email:</strong> ' . $row['Email'] . '</p>';
                echo '<form method="POST" action="">';
                echo '<input type="hidden" name="unblock_email" value="' . $row['Email'] . '">';
                echo '<button type="submit" name="unblock" class="unblock-button">Unblock</button>';
                echo '</form>';
                echo '</div>';
            }
        } else {
            echo "<p>No blocked customers found.</p>";
        }
        ?>
    </div>
</body>
</html>

