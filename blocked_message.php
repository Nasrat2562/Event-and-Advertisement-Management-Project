<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Blocked</title>
    <style>
        body {
            background-image: url('blocked_image.jpg'); /* Add your blocked image URL here */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .message {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
        }
        h1 {
            margin-top: 0;
        }
        p {
            margin-bottom: 20px;
        }
        a {
            color: #ffc107; /* Change the link color as needed */
        }
    </style>
</head>
<body>
    <div class="message">
        <h1>Your account is blocked</h1>
        <p>Contact the administrator for further assistance.</p>
        <a href="admin_login.php">Back to login</a>
    </div>
</body>
</html>
