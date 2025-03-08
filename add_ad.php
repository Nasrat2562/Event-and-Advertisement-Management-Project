<?php
session_start();

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

// Initialize variables to store form data
$adTitle = $description = $videoPath = "";
$advertiserID = $_SESSION['advertiser_id'] ?? null; // Retrieve advertiser ID from session

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $adTitle = $_POST['ad_title'] ?? '';
    $description = $_POST['description'] ?? '';

    // Check if a file was uploaded
    if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
        // Define upload directory and file path
        $uploadDir = 'uploads/';
        $videoName = $_FILES['video']['name'];
        $videoPath = $uploadDir . $videoName;

        // Move uploaded file to the upload directory
        if (move_uploaded_file($_FILES['video']['tmp_name'], $videoPath)) {
            // Prepare and execute SQL statement to insert data into Advertisement table
            $stmt = $conn->prepare("INSERT INTO Advertisement (Title, Description, Video_path, AdvertiserID) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $adTitle, $description, $videoPath, $advertiserID);

            if ($stmt->execute() === TRUE) {
                // Redirect to a success page or display a success message
                header("Location: all_ads.php");
                exit;
            } else {
                $error = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Failed to upload file.";
        }
    } else {
        $error = "No file uploaded or an error occurred.";
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Advertisement</title>
    <!-- Add Bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-image: url('image27.jpg'); /* Adjust the path to your image */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.8); /* Add transparency */
            padding: 20px;
            border-radius: 10px;
            margin-top: 50px; /* Adjust as needed */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Add New Advertisement</h1>
        <hr>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <!-- Display error message if any -->
                <?php if (!empty($error)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php } ?>
                <!-- Advertisement form -->
               <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="adTitle" class="form-label">Type</label>
                        <input type="text" class="form-control" id="adTitle" name="ad_title" value="<?php echo $adTitle; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="adDescription" class="form-label">Advertisement Description</label>
                        <textarea class="form-control" id="adDescription" name="description" rows="3" required><?php echo $description; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="adVideo" class="form-label">Advertisement Video</label>
                        <!-- File input field for video -->
                        <input class="form-control" type="file" id="adVideo" name="video" accept="video/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Bootstrap JS link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>












