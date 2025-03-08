<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "shop_db"; 


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM Event";
$result = $conn->query($sql);


$events = [];


if ($result->num_rows > 0) {
   
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        
        .navbar-custom {
            background-color: #007bff; 
        }
        .navbar-custom .navbar-brand {
            color: #fff; 
        }
        .navbar-custom .navbar-nav .nav-link {
            color: #fff; 
        }
        .jumbotron-custom {
            background-image: url('image12.jpeg'); 
            color: #fff; 
            text-align: center;
        }
    </style>
</head>
<body>


<?php
// Other code remains unchanged
?>
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Event</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="Venue.php">Venue</a> 
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="BookingHistory.php">Booking History</a>
                </li>
                <!-- Add Review link -->
                <li class="nav-item">
                    <a class="nav-link" href="add_review.php">Add Review</a>
                </li>
                <!-- See All Advertisement link -->
                <li class="nav-item">
                    <a class="nav-link" href="Advertisement.php">See All Advertisement</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<div class="jumbotron jumbotron-fluid jumbotron-custom">
    <div class="container">
        <h1 class="display-4">Upcoming Events</h1>
        <div id="events-container" class="row">
            <?php
            
            foreach ($events as $event) {
                echo '<div class="col-md-4 mb-4">';
                echo '<div class="card">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $event["Name"] . '</h5>';
                echo '<p class="card-text">' . $event["Description"] . '</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-lg navbar-custom">
   
</nav>


<div class="jumbotron jumbotron-fluid jumbotron-custom">
   
</div>


<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <?php
            
            if (isset($_SESSION['customer_id'])) {
                echo '<a href="Booking.php" class="btn btn-primary">View Booking History</a>';
            }
            ?>
        </div>
    </div>
</div>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
 </body>
</html>







