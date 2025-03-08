<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ad Types</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-image: url('image26.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .container {
            margin-top: 50px;
            background-color: rgba(255, 255, 255, 0.8); /* Adjust the background opacity */
            padding: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Ad Types</h2>
        <div class="row">
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "shop_db";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM Ad_Type";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="<?php echo $row['Ad_image']; ?>" class="card-img-top" alt="Ad Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['Ad_type']; ?></h5>
                                <p class="card-text"><?php echo $row['Ad_title']; ?></p>
                                <!-- Updated anchor tag to pass ad_type_id as query parameter -->
                                <a href="add_ad.php?ad_type_id=<?php echo $row['ID']; ?>" class="btn btn-primary">Add New Ad</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "No ads found.";
            }

            $conn->close();
            ?>
        </div>
        <button class="btn btn-primary schedule-btn" onclick="redirectToEvent()">Close</button>
</div>

<script>
    function redirectToEvent() {
        window.location.href = 'Advertisement.php';
    }
</script>
    
</body>
</html>






