
<?php

 

$servername = "localhost";

$username = "root";

$password = "";

$dbname = "Projectt";

 

$conn = new mysqli($servername, $username, $password, $dbname);

 

if ($conn->connect_error) {

    die("Connection failed: " . $conn->connect_error);

}

 

$sql = "SELECT * FROM Advertisement";

$result = $conn->query($sql);

?>

 

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Advertisement Page</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>

     

        .navbar-custom {

            background-color: #800080;

        }

        .navbar-custom .navbar-brand {

            color: #fff;

        }

        .navbar-custom .navbar-nav .nav-link {

            color: #fff;

        }

        .advertisement {

            margin-bottom: 20px;

            padding: 10px;

            border: 1px solid #ccc;

            border-radius: 5px;

        }

    </style>

</head>

<body>

 

<nav class="navbar navbar-expand-lg navbar-custom">

    <div class="container-fluid">

        <a class="navbar-brand" href="#">Advertisement Page</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">

                    <a class="nav-link" href="advertiser_login.php">Create Advertiser Account</a>

                </li>

            </ul>

        </div>

    </div>

</nav>

 

<div class="container mt-4">

    <?php

 

    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {

            echo '<div class="advertisement">';

            echo "<h2>Title: " . $row["Title"] . "</h2>";

            echo "<p>Description: " . $row["Description"] . "</p>";

            echo '<video controls autoplay muted style="max-width: 100%;">

                    <source src="' . $row["Video_path"] . '" type="video/mp4">

                    Your browser does not support the video tag.

                  </video>';

            echo '<div class="mt-3">';

            echo '<button class="btn btn-success ms-2" onclick="searchPlayStore(\'' . $row["Title"] . '\')">Install</button>';

            echo '</div>';

            echo '</div>';

        }

    } else {

        echo "No advertisements found.";

    }

 

    $conn->close();

    ?>

</div>

 

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

 

<script>

    function searchPlayStore(title) {

        var searchUrl = 'https://play.google.com/store/search?q=' + encodeURIComponent(title);

        window.open(searchUrl, '_blank');

    }

 

   

    $(document).ready(function() {

        $('.block-advertiser').click(function() {

            var advertiserId = $(this).data('advertiser-id');

            $.ajax({

                url: 'block_advertiser.php',

                type: 'POST',

                data: { advertiserId: advertiserId },

                success: function(response) {

                    if (response === 'success') {

                        alert('Advertiser has been blocked.');

                        location.reload();

                    } else {

                        alert('Failed to block advertiser.');

                    }

                }

            });

        });

    });

</script>

 

</body>

</html>