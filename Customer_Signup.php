<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "shop_db";


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $Username = $_POST['username'];
    $Email = $_POST['email'];
    $Password = $_POST['password'];

    $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);


    $stmt = $conn->prepare("INSERT INTO Customer_Signup (Username, Email, Password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $Username, $Email, $hashedPassword);

    if ($stmt->execute()) {
        echo '<script>alert("Sign up successful!");</script>';
      
        header("Location: Event.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
  
// Start the session
session_start();

// Assuming login process sets $_SESSION['email'] after successful login
// Retrieve customer_id based on the logged-in user's email
$loggedin_email = $_SESSION['email'];

// Perform database query to get customer_id based on email
$customer_query = "SELECT ID FROM Customer_Signup WHERE Email = '$loggedin_email'";
$customer_result = mysqli_query($conn, $customer_query);

// Check if customer exists
if (mysqli_num_rows($customer_result) > 0) {
    $customer_data = mysqli_fetch_assoc($customer_result);
    $_SESSION['customer_id'] = $customer_data['ID'];
} else {
    // Handle case where customer does not exist
}
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-image: url('image34.avif');
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
            max-width: 400px;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">Customer Sign Up</h2>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
