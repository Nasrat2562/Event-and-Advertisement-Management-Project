<?php
// Assuming $accountNumber contains the account number obtained from the confirmation page
$venueId = $_GET['venue_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <!-- Add your CSS stylesheets if needed -->
</head>
<body>

    <h1>Confirm Booking</h1>
    <form action="Booking.php" method="post">
        <label for="accountNumber">Account Number:</label>
        <input type="text" id="accountNumber" name="Account_number" required><br><br>
        
        <label for="pin">PIN:</label>
        <input type="password" id="pin" name="pin" required><br><br>
        
        <input type="hidden" name="venue_id" value="<?php echo $venueId; ?>">
        
        <button type="submit">Confirm</button>
    </form>
</body>
</html>

