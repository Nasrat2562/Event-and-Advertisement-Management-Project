<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop_db";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["ad_image"])) {
    
    $ad_type = $_POST['ad_type'];
    $ad_title = $_POST['ad_title'];

   
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["ad_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    
    $check = getimagesize($_FILES["ad_image"]["tmp_name"]);
    if($check !== false) {
       
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    
    if ($_FILES["ad_image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

   
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" && $imageFileType != "webp") {
        echo "Sorry, only JPG, JPEG, PNG, GIF, and WebP files are allowed.";
        $uploadOk = 0;
    }

    
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    
    } else {
     
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        if (move_uploaded_file($_FILES["ad_image"]["tmp_name"], $target_file)) {
            
            $sql = "INSERT INTO Ad_Type (Ad_type, Ad_title, Ad_image) VALUES ('$ad_type', '$ad_title', '$target_file')";
            if ($conn->query($sql) === TRUE) {
                
                header("Location: Ad_Type.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
} else {
    //echo "Please select a file.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advertiser Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
         body {
            background-image: url('image14.jpeg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Select ad type</h2>
        <div class="row">
          
        </div>
        <hr>
        <h3 class="mb-3">Add New Ad type</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="ad_type" class="form-label">Ad Type</label>
                <input type="text" class="form-control" id="ad_type" name="ad_type" required>
            </div>
            <div class="mb-3">
                <label for="ad_title" class="form-label">Description</label>
                <input type="text" class="form-control" id="ad_title" name="ad_title" required>
            </div>
            <div class="mb-3">
                <label for="ad_image" class="form-label">Upload Image</label>
                <input type="file" class="form-control" id="ad_image" name="ad_image" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Ad</button>
        </form>
    </div>
</body>
</html>




