<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Review</title>
    <style>
      body {
            background-image: url('image11.jpg'); /* Replace 'your_background_image.jpg' with the path to your background image */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            max-width: 1200px;
            margin: 0 auto;
        }
        .card {
            width: 300px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px;
            padding: 20px;
        }
        .card h2 {
            margin-top: 0;
        }
        .form-group {
            margin-top: 20px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group button {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
    <script>
        function redirectToVenueDetails() {
            window.location.href = "Venue_Details.php";
        }
    </script>
</head>
<body>
    <h1>Add Review</h1>
    <div class="container">
    <?php
    // Start the session
    session_start();

    // Check if user is logged in
    if (!isset($_SESSION['customer_id'])) {
        // Redirect to login page or display error message
        // Example:
        header("Location: Customer_Login.php");
        exit(); // Make sure to exit after redirection
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_review'])) {
        // Establish database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "shop_db";

        $conn = mysqli_connect($servername, $username, $password, $dbname);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Retrieve data from the form
        $venue_id = $_POST['venue_id'];
        $review = $_POST['review'];
        $rating = $_POST['rating'];
        $customer_id = $_SESSION['customer_id']; // Use customer_id from session

        // Insert the review into the database
        $insert_query = "INSERT INTO reviews (Venue_id, Review, Rating, Created_at, Customer_id) 
                         VALUES ('$venue_id', '$review', '$rating', NOW(), '$customer_id')";

        if (mysqli_query($conn, $insert_query)) {
            // Redirect to Venue_Details page after successful review submission
            header("Location: Venue.php?venue_id=$venue_id");
            exit();
        } else {
            echo "Error: " . $insert_query . "<br>" . mysqli_error($conn);
        }

        // Close database connection
        mysqli_close($conn);
    }
    ?>



    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "shop_db";

    $conn = mysqli_connect($servername, $username, $password, $dbname);


    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $venues_query = "SELECT * FROM Venue";
    $venues_result = mysqli_query($conn, $venues_query);

    // Loop through each venue
    while ($venue = mysqli_fetch_assoc($venues_result)) {
        $venue_id = $venue['Venue_id'];
        $venue_name = $venue['Vanue_Name'];
        ?>
        <div class="card">
            <h2><?php echo $venue_name; ?></h2>
            <form action="add_review.php" method="POST">
    <input type="hidden" name="venue_id" value="<?php echo $venue_id; ?>">
    <div class="form-group">
        <label for="review_<?php echo $venue_id; ?>">Review:</label>
        <textarea id="review_<?php echo $venue_id; ?>" name="review" rows="4" required></textarea>
    </div>
    <div class="form-group">
        <label for="rating_<?php echo $venue_id; ?>">Rating:</label>
        <select id="rating_<?php echo $venue_id; ?>" name="rating" required>
            <option value="">Select Rating</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
    </div>
    <div class="form-group">
        <button type="submit" name="submit_review">Submit Review</button>
    </div>
</form>
        </div>
        <?php
    }
    ?>
    </div>
</body>
</html>




