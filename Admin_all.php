<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch venue data
$sql_venue = "SELECT * FROM Venue";
$result_venue = mysqli_query($conn, $sql_venue);

// Fetch event data
$sql_event = "SELECT * FROM Event";
$result_event = mysqli_query($conn, $sql_event);

// Fetch ad type data
$sql_ad_type = "SELECT * FROM Ad_Type";
$result_ad_type = mysqli_query($conn, $sql_ad_type);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            background-image: url('image29.avif'); /* Replace 'your_background_image.jpg' with the path to your background image */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px; /* Adjust spacing as needed */
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Venues</h2>
    <table>
        <tr>
            <th>Venue ID</th>
            <th>Venue Name</th>
            <th>Location</th>
            <th>Price</th>
            <th>Event ID</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result_venue)): ?>
        <tr>
            <td><?php echo $row['Venue_id']; ?></td>
            <td><?php echo $row['Vanue_Name']; ?></td>
            <td><?php echo $row['Location']; ?></td>
            <td><?php echo $row['Price']; ?></td>
            <td><?php echo $row['Event_Id']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h2>Events</h2>
    <table>
        <tr>
            <th>Event ID</th>
            <th>Name</th>
            <th>Description</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result_event)): ?>
        <tr>
            <td><?php echo $row['Event_Id']; ?></td>
            <td><?php echo $row['Name']; ?></td>
            <td><?php echo $row['Description']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h2>Ad Types</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Ad Type</th>
            <th>Title</th>
            <th>Image</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result_ad_type)): ?>
        <tr>
            <td><?php echo $row['ID']; ?></td>
            <td><?php echo $row['Ad_type']; ?></td>
            <td><?php echo $row['Ad_title']; ?></td>
            <td><?php echo $row['Ad_image']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
