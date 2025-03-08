<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Advertisement</title>
    <!-- Add any necessary CSS stylesheets -->
</head>
<body>
    <div class="container">
        <h2>Gaming Advertisement</h2>
        <!-- Your gaming advertisement goes here -->
        <video id="adVideo" controls autoplay>
            <source src="gaming_advertisement.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <!-- Button to redirect to Google Play Store -->
        <button id="playStoreButton" style="display: none;">Play Now</button>
    </div>

    <script>
        // JavaScript code to detect video end and handle button click
        document.getElementById('adVideo').addEventListener('ended', function() {
            // Show the play store button when the video ends
            document.getElementById('playStoreButton').style.display = 'block';
        });

        // Function to redirect to the Google Play Store page
        document.getElementById('playStoreButton').addEventListener('click', function() {
            // Redirect to the Google Play Store URL
            window.location.href = 'https://play.google.com/store/apps/details?id=com.king.candycrushsaga&hl=en&gl=US';
        });
    </script>
</body>
</html>

