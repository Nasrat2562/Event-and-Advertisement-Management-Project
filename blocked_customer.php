<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop_db";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unblock'])) {
    $email_to_unblock = $_POST['email'];
    $unblock_sql = "UPDATE Customer_signup SET blocked = 0 WHERE Email = '$email_to_unblock'";
    if (mysqli_query($conn, $unblock_sql)) {
        echo "<script>alert('Customer unblocked successfully');</script>";
    } else {
        echo "<script>alert('Error unblocking customer');</script>";
    }
}

$sql = "SELECT Email FROM Customer_signup WHERE blocked = 1";
$result = mysqli_query($conn, $sql);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blocked Customers</title>
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
        .blocked-customer {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .blocked-customer p {
            margin: 10px 0;
        }
        .unblock-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            border: none;
            cursor: pointer;
        }
        .unblock-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Blocked Customers</h1>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="blocked-customer">';
                echo '<p><strong>Email:</strong> ' . $row['Email'] . '</p>';
                echo '<form method="POST" action="">';
                echo '<input type="hidden" name="email" value="' . $row['Email'] . '">';
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


