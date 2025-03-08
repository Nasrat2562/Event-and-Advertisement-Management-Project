<?php
session_start();

// Simulate points (in a real implementation, fetch from database)
if (!isset($_SESSION['points'])) {
    $_SESSION['points'] = 0;
}

// Handle form submission to add points
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_points'])) {
    $_SESSION['points'] += 10; // Add 10 points for watching the ad
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reward-Based Viewing</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        .ad-container {
            text-align: center;
            margin-top: 50px;
        }
        .ad-video {
            width: 100%;
            max-width: 600px;
            height: 340px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="my-4">Watch this Ad and Earn Points</h1>
    <div class="ad-container">
        <video class="ad-video" controls>
            <source src="your-ad-video.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <form method="POST">
            <button type="submit" name="add_points" class="btn btn-primary">I've Watched the Ad</button>
        </form>
        <p class="mt-4">Your Points: <?php echo $_SESSION['points']; ?></p>
    </div>
</div>
</body>
</html>
