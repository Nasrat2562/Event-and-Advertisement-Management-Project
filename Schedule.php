<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "shop_db";


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if(isset($_GET['Venue_id'])) {
    $Venue_id = $_GET['Venue_id'];

    
    $sql = "SELECT * FROM Schedule WHERE Venue_id = $Venue_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      
        $schedule = [];

       
        while ($row = $result->fetch_assoc()) {
            $schedule[] = $row;
        }
    } else {
        $schedule = []; 
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-image: url('image28.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        
        .schedule-btn {
            margin-top: 10px; 
        }
       
        table {
            background-color: rgba(173, 216, 230, 0.8); 
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="my-4">Schedule</h1>

    <?php if (!empty($schedule)): ?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Schedule_id</th>
                    <th scope="col">Venue_id</th>
                    <th scope="col">Schedule_date</th>
                    <th scope="col">Schedule_time</th>
                    <th scope="col">Duration</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schedule as $event): ?>
                    <tr>
                        <td><?php echo $event['Schedule_id']; ?></td>
                        <td><?php echo $event['Venue_id']; ?></td>
                        <td><?php echo $event['Schedule_date']; ?></td>
                        <td><?php echo $event['Schedule_time']; ?></td>
                        <td><?php echo $event['Duration']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No schedule available for this venue.</p>
    <?php endif; ?>
    
    <!-- Close button -->
    <button class="btn btn-primary schedule-btn" onclick="redirectToEvent()">Close</button>
</div>

<script>
    function redirectToEvent() {
        window.location.href = 'Event.php';
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>




