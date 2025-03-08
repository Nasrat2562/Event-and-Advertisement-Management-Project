<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "shop_db";

$conn = new mysqli($servername, $username, $password, $database);

// Function to add a schedule to the database
function addSchedule($venue_id, $schedule_date, $schedule_time, $duration) {
    global $conn; // Assuming $conn is your database connection
    
    // Prepare and execute SQL statement
    $stmt = $conn->prepare("INSERT INTO schedule(Venue_id, Schedule_date, Schedule_time, Duration) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issi", $venue_id, $schedule_date, $schedule_time, $duration);
    $stmt->execute();
    
    // Check if insertion was successful
    if ($stmt->affected_rows == 1) {
        return true;
    } else {
        return false;
    }
}

// Function to get venue details along with event names
function getVenueDetails() {
    global $conn; // Assuming $conn is your database connection
    
    $sql = "SELECT v.Venue_id, v.Vanue_Name, e.Name FROM venue v
            LEFT JOIN Event e ON v.Event_id = e.Event_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $venue_id = $_POST["venue_id"];
    $schedule_date = $_POST["schedule_date"];
    $schedule_time = $_POST["schedule_time"];
    $duration = $_POST["duration"];
    
    // Add schedule to the database
    if (addSchedule($venue_id, $schedule_date, $schedule_time, $duration)) {
        echo "Schedule added successfully.";
    } else {
        echo "Error adding schedule.";
    }
}

// Get venue details
$venues = getVenueDetails();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Schedule</title>
    <style>
        body {
            background-image: url('image42.jpeg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .container {
            padding: 20px;
            margin-top: 50px;
        }
        .venue-card {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Schedule</h2>
        
        <?php foreach ($venues as $venue): ?>
            <div class="venue-card">
                <h3><?php echo $venue['Vanue_Name']; ?></h3>
                <p>Event: <?php echo $venue['Name']; ?></p>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" name="venue_id" value="<?php echo $venue['Venue_id']; ?>">
                    Schedule Date: <input type="date" name="schedule_date"><br><br>
                    Schedule Time: <input type="time" name="schedule_time"><br><br>
                    Duration (in minutes): <input type="number" name="duration"><br><br>
                    <input type="submit" value="Add Schedule">
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>



