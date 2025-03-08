<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "shop_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists and get their account status
    $login_sql = "SELECT Id, Email, Password, status FROM sign_up WHERE Email = ?";
    $stmt = $conn->prepare($login_sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $dbEmail, $dbPassword, $status);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        if ($status == 1 && password_verify($password, $dbPassword)) {
            // Successful login
            $_SESSION['advertiser_id'] = $id;
            $_SESSION['email'] = $dbEmail;
            header("Location: Ad_Type.php");
            exit(); // It's good practice to exit after a redirect
        } elseif ($status == 0) {
            // Account is blocked
            echo "Your account has been blocked by the admin. Please contact support for assistance.";
        } else {
            // Invalid credentials
            echo "Invalid email or password.";
        }
    } else {
        // User does not exist
        echo "User with this email does not exist.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advertiser Account Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
     
        .container {
            max-width: 400px;
            margin-top: 50px;
        }
        body {
            background-image: url('image8.jpeg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">Advertiser Account Login</h2>
    
    <form method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>
    <hr>
    
    <p class="text-center">Don't have an account? <a href="Sign_up.php">Sign up here</a></p>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>








