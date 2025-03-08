<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "shop_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch event data from the Event table
$sql_event = "SELECT * FROM Event";
$result_event = mysqli_query($conn, $sql_event);

if (!$result_event) {
    die("Error fetching event data: " . mysqli_error($conn));
}

// Check if the form is submitted
if(isset($_POST['submit'])) {
    $venue_Name = $_POST['venue_Name'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $event_id = $_POST['event_id'];

    // Handle file upload
    $targetDirectory = "uploads/";
    $uploadedFiles = [];
    $uploadOk = 1;

    // Loop through each uploaded file
    foreach($_FILES['venue_image']['name'] as $key => $value) {
        $targetFile = $targetDirectory . basename($_FILES["venue_image"]["name"][$key]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if file is an actual image
        $check = getimagesize($_FILES["venue_image"]["tmp_name"][$key]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["venue_image"]["size"][$key] > 500000) {
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $uploadOk = 0;
        }

        // If everything is ok, try to upload file
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["venue_image"]["tmp_name"][$key], $targetFile)) {
                $uploadedFiles[] = $targetFile;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }

    // Insert venue data into Venue table
    $sql = "INSERT INTO Venue (Vanue_Name, Location, Price, Event_Id, Venue_Image) VALUES ('$venue_Name', '$location', '$price', '$event_id', '" . implode(",", $uploadedFiles) . "')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Venue type added successfully!'); window.location='Venue_Details.php?Venue_id=" . mysqli_insert_id($conn) . "';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Venue Type</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-image: url('image30.webp'); /* Change 'background-image.jpg' to your actual image name */
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
        <h2 class="my-4">Add Venue Type</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="venue_Name" class="form-label">Venue Name</label>
                <input type="text" class="form-control" id="venue_Name" name="venue_Name" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="text" class="form-control" id="price" name="price" required>
            </div>
            <div class="mb-3">
                <label for="event_id" class="form-label">Event</label>
                <select class="form-select" id="event_id" name="event_id" required>
                    <option value="">Select Event</option>
                    <?php while($row_event = mysqli_fetch_assoc($result_event)): ?>
                        <option value="<?php echo $row_event['Event_Id']; ?>"><?php echo $row_event['Name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="venue_image" class="form-label">Venue Images</label>
                <input type="file" class="form-control" id="venue_image" name="venue_image[]" accept="image/*" multiple required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>




