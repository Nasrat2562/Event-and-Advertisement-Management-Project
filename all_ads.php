<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to select all ads
$sql = "SELECT * FROM Advertisement";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Ads</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h2>All Ads</h2>
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Display each ad as a card
                    echo '<div class="col-md-4">';
                    echo '<div class="card mb-4">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $row["Title"] . '</h5>';
                    echo '<p class="card-text">' . $row["Description"] . '</p>';
                    // Embed the video using the video tag
                    echo '<video id="adVideo' . $row["ID"] . '" controls autoplay muted style="max-width: 100%;">';
                    echo '<source src="' . $row["Video_path"] . '" type="video/mp4">';
                    echo 'Your browser does not support the video tag.';
                    echo '</video>';
                    // Add Skip and Install buttons
                    echo '<div class="mt-3">';
                  
                    echo '<button class="btn btn-success ms-2" onclick="searchPlayStore(\'' . $row["Title"] . '\')">Install</button>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "No ads found.";
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- JavaScript for skipping video and searching Play Store -->
    <script>

        function searchPlayStore(title) {
            var searchUrl = 'https://play.google.com/store/search?q=' + encodeURIComponent(title);
            window.open(searchUrl, '_blank');
        }
    </script>
</body>
</html>

















