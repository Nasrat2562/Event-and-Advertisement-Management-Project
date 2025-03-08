<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "shop_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the customer ID
$customer_id = $_SESSION['customer_id'];

// Get the last displayed ad ID for this customer
$tracker_sql = "SELECT LastAdID FROM CustomerAdTracker WHERE CustomerID = ?";
$stmt = $conn->prepare($tracker_sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$stmt->bind_result($last_ad_id);
$stmt->fetch();
$stmt->close();

if ($last_ad_id === null) {
    $last_ad_id = 0;
}

// Get the next ad
$next_ad_sql = "SELECT * FROM Advertisement WHERE ID > ? ORDER BY ID ASC LIMIT 1";
$stmt = $conn->prepare($next_ad_sql);
$stmt->bind_param("i", $last_ad_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $ad = $result->fetch_assoc();
    $next_ad_id = $ad["ID"];

    // Update the tracker
    $update_tracker_sql = "INSERT INTO CustomerAdTracker (CustomerID, LastAdID) VALUES (?, ?) ON DUPLICATE KEY UPDATE LastAdID = ?";
    $stmt = $conn->prepare($update_tracker_sql);
    $stmt->bind_param("iii", $customer_id, $next_ad_id, $next_ad_id);
    $stmt->execute();
} else {
    // If no more ads, reset to the first ad
    $reset_ad_sql = "SELECT * FROM Advertisement ORDER BY ID ASC LIMIT 1";
    $reset_result = $conn->query($reset_ad_sql);

    if ($reset_result->num_rows > 0) {
        $ad = $reset_result->fetch_assoc();
        $next_ad_id = $ad["ID"];

        // Update the tracker
        $update_tracker_sql = "INSERT INTO CustomerAdTracker (CustomerID, LastAdID) VALUES (?, ?) ON DUPLICATE KEY UPDATE LastAdID = ?";
        $stmt = $conn->prepare($update_tracker_sql);
        $stmt->bind_param("iii", $customer_id, $next_ad_id, $next_ad_id);
        $stmt->execute();
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Next Ad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h2>Next Ad</h2>
        <div class="row">
            <?php if (!empty($ad)) { ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $ad["Title"]; ?></h5>
                            <p class="card-text"><?php echo $ad["Description"]; ?></p>
                            <video id="adVideo<?php echo $ad["ID"]; ?>" controls autoplay muted style="max-width: 100%;">
                                <source src="<?php echo $ad["Video_path"]; ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <div class="mt-3">
                                <button class="btn btn-primary" onclick="skipVideo(<?php echo $ad["ID"]; ?>)">Skip</button>
                                <button class="btn btn-success ms-2" onclick="searchPlayStore('<?php echo $ad["Title"]; ?>')">Install</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <p>No ads found.</p>
            <?php } ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- JavaScript for skipping video and searching Play Store -->
    <script>
        function skipVideo(adId) {
            window.location.href = 'Event.php';
        }

        function searchPlayStore(title) {
            var searchUrl = 'https://play.google.com/store/search?q=' + encodeURIComponent(title);
            window.open(searchUrl, '_blank');
        }
    </script>
</body>
</html>
