<?php
// Retrieve ad details based on the ID passed in the URL parameter
$adId = $_GET['id'];

// Fetch ad details from the database or any other source
// Replace this with your actual code to fetch ad details
$adDetails = [
    'title' => 'Ad Title ' . $adId,
    'description' => 'Description of Ad ' . $adId,
    // Add more details as needed
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advertisement Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<div class="container">
    <h1>Advertisement Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?php echo $adDetails['title']; ?></h5>
            <p class="card-text"><?php echo $adDetails['description']; ?></p>
            <!-- Add more details here -->
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
