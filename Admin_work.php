<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "shop_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch unread questions count
$unread_questions_sql = "SELECT COUNT(*) as unread_count FROM VenueQuestions WHERE IsRead = FALSE";
$unread_questions_result = $conn->query($unread_questions_sql);
$unread_questions_count = 0;
if ($unread_questions_result->num_rows > 0) {
    $row = $unread_questions_result->fetch_assoc();
    $unread_questions_count = $row['unread_count'];
}

// Fetch unread questions
$questions_sql = "SELECT q.ID, q.Question, u.Username 
                  FROM VenueQuestions q
                  LEFT JOIN Customer_signup u ON q.Customer_id = u.ID
                  WHERE q.IsRead = FALSE";
$questions_result = $conn->query($questions_sql);

// Fetch unread bookings count
$unread_bookings_sql = "SELECT COUNT(*) as unread_count FROM Booking WHERE IsRead = FALSE";
$unread_bookings_result = $conn->query($unread_bookings_sql);
$unread_bookings_count = 0;
if ($unread_bookings_result->num_rows > 0) {
    $row = $unread_bookings_result->fetch_assoc();
    $unread_bookings_count = $row['unread_count'];
}

// Fetch unread bookings
$bookings_sql = "SELECT b.Booking_ID, b.Date, u.Username 
                 FROM Booking b
                 LEFT JOIN Customer_signup u ON b.Customer_id = u.ID
                 WHERE b.IsRead = FALSE";
$bookings_result = $conn->query($bookings_sql);

// Fetch all advertisements
$ads_sql = "SELECT ID, Title, Description, Video_path FROM advertisement";
$ads_result = $conn->query($ads_sql);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-image: url('image40.png');
            background-color: #f0f0f0; /* Fallback color in case the background image doesn't load */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #343a40; /* Darker shade */
        }
        .navbar-brand {
            color: white;
            font-size: 20px; /* Larger font size */
        }
        .navbar-nav .nav-link {
            color: white;
            font-size: 18px; /* Larger font size */
        }
        .navbar-nav .nav-link:hover {
            color: #f0f0f0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            padding: 20px; /* Optional: Add padding for better spacing */
            border-radius: 10px; /* Optional: Add border-radius for rounded corners */
        }
        .card {
            background-image: url('image42.jpeg'); /* Path to your background image */
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center; /* Optional: Adjust the position of the background image */
            width: 200px;
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .option a {
            display: block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s ease;
        }
        .option a:hover {
            background-color: #0056b3;
        }
        .notification {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .notification-icon {
            font-size: 24px;
            cursor: pointer;
        }
        .notification-count {
            position: absolute;
            top: -10px;
            right: -10px;
            background: red;
            color: white;
            border-radius: 50%;
            padding: 5px 10px;
            font-size: 12px;
        }
        .notification-dropdown {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 300px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }
        .notification:hover .notification-dropdown {
            display: block;
        }
        .notification-item {
            padding: 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        .notification-item:last-child {
            border-bottom: none;
        }
    </style>
    <script>
        function markAsRead(type, id) {
            fetch('update_notification_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ type: type, id: id }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(type + '-' + id).remove();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</head>
<body>
<nav class="navbar navbar-expand-lg">
<div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                        <a class="nav-link" href="blocked_customer.php">Blocked Customers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="blocked_list.php">Blocked Advertiser</a>
                    </li>
                </ul>
          </div>
    </nav>

    <div class="notification">
        <span class="notification-icon">&#128276;</span>
        <?php if ($unread_questions_count > 0 || $unread_bookings_count > 0): ?>
            <span class="notification-count"><?php echo $unread_questions_count + $unread_bookings_count; ?></span>
            <div class="notification-dropdown">
                <strong>Unread Questions:</strong>
                <?php while ($row = $questions_result->fetch_assoc()): ?>
                    <div class="notification-item" id="question-<?php echo $row['ID']; ?>">
                        <strong><?php echo $row['Username']; ?></strong>: <?php echo $row['Question']; ?>
                        <a href="admin_questions.php?id=<?php echo $row['ID']; ?>" 
                           class="btn btn-sm btn-primary" 
                           onclick="markAsRead('question', <?php echo $row['ID']; ?>)">Answer</a>
                    </div>
                <?php endwhile; ?>
                <strong>Unread Bookings:</strong>
                <?php while ($row = $bookings_result->fetch_assoc()): ?>
                    <div class="notification-item" id="booking-<?php echo $row['Booking_ID']; ?>">
                        <strong><?php echo $row['Username']; ?></strong>: <?php echo $row['Date']; ?>
                        <a href="All_Booking.php?id=<?php echo $row['Booking_ID']; ?>" 
                           class="btn btn-sm btn-primary" 
                           onclick="markAsRead('booking', <?php echo $row['Booking_ID']; ?>)">View</a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="container">
        <div class="card">
            <h3>Add Event</h3>
            <div class="option">
                <a href="Event_Type.php">Submit</a>
            </div>
        </div>
        
        <div class="card">
            <h3>Add Ad Type</h3>
            <div class="option">
                <a href="Select_Ad_Type.php">Submit</a>
            </div>
        </div>
        <div class="card">
            <h3>Add Venue</h3>
            <div class="option">
                <a href="Venue_Type.php">Submit</a>
            </div>
        </div>
        <div class="card">
            <h3>All Bookings</h3>
            <div class="option">
                <a href="All_Booking.php">View</a>
            </div>
        </div>
        <div class="card">
            <h3>Customer Reviews</h3>
            <div class="option">
                <a href="All_Reviews.php">See all</a>
            </div>
        </div>
        <div class="card">
            <h3>Add schedule for venue</h3>
            <div class="option">
                <a href="schedule_type.php">Add</a>
            </div>
        </div>
        <div class="card">
            <h3>Customer Questions</h3>
            <div class="option">
                <a href="admin_questions.php">View</a>
            </div>
        </div>
        <div class="card">
            <h3>All Advertisements</h3>
            <div class="option">
                <a href="admin_ads.php">View</a>
            </div>
        </div>
    </div>
</body>
</html>







