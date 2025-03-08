<?php
// Database configuration
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: Customer_Login.php");
    exit;
}

// Your Venue.php form code here

// Include the customer_id in the form submission
echo '<input type="hidden" name="customer_id" value="' . $_SESSION['customer_id'] . '">';

// Check if user is not logged in, redirect to login page
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

// Fetch venue data from the Venue table
$sql = "SELECT v.*, e.Name 
FROM Venue v 
LEFT JOIN Event e ON v.Event_Id = e.Event_Id";
$result = $conn->query($sql);

// Initialize an empty array to store venue data
$venues = [];

// Check if there are any venues
if ($result->num_rows > 0) {
    // Fetch each row of data and add it to the $venues array
    while ($row = $result->fetch_assoc()) {
        $venues[] = $row;
    }
}


// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venue Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-image: url('image22.webp');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .card {
            background-image: url('imageforcardbackgroundvenue.jpeg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            color: #ffffff; /* Change text color to ensure readability */
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="my-4">All Venues</h1>
   
    <div class="row">
        <?php foreach ($venues as $venue): ?>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $venue['Vanue_Name']; ?></h5>
                    <p class="card-text">Location: <?php echo $venue['Location']; ?></p>
                    <p class="card-text">Price: <?php echo $venue['Price']; ?></p>
                    <p class="card-text">Event: <?php echo $venue['Name']; ?></p>
                    <!-- Hidden input field for venue ID -->
                    <input type="hidden" name="venue_id" value="<?php echo $venue['Venue_id']; ?>">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookingModal<?php echo $venue['Venue_id']; ?>">
                        Book Now
                    </button>
                    <!-- Details button with modified URL -->
                    <a href="venue_Details.php?Venue_id=<?php echo $venue['Venue_id']; ?>" class="btn btn-secondary">Details</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modals -->
<?php foreach ($venues as $venue): ?>
    <div class="modal fade" id="bookingModal<?php echo $venue['Venue_id']; ?>" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-image: url('image23.webp');">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingModalLabel">Book Venue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="Booking.php" method="post">
                    <div class="mb-3">
                        <label for="paymentMethod" class="form-label" style="font-weight: bold; font-size: larger;">Payment Method</label>
                        <select class="form-select" id="paymentMethod" name="paymentMethod">
                            <option selected>Select Payment Method</option>
                            <option value="credit">Credit Card</option>
                            <option value="debit">Debit Card</option>                            
                            <option value="Bkash">Bkash</option>
                            <option value="Rocket">Rocket</option>
                            <option value="Nagad">Nagad</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="bookingDate" class="form-label" style="font-weight: bold; font-size: larger;">Booking Date</label>
                        <input type="date" class="form-control" id="bookingDate" name="bookingDate" min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="accountNumber" class="form-label" style="font-weight: bold; font-size: larger;">Account Number</label>
                        <input type="text" class="form-control" id="accountNumber" name="Account_number" placeholder="Enter Account Number" required>
                    </div>
                    <div class="mb-3">
                        <label for="pin" class="form-label" style="font-weight: bold; font-size: larger;">PIN</label>
                        <input type="password" class="form-control" id="pin" name="pin" placeholder="Enter PIN" required>
                    </div>
                    <!-- Hidden field to pass venue_id -->
                    <input type="hidden" name="venue_id" value="<?php echo $venue['Venue_id']; ?>">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?php endforeach; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

















