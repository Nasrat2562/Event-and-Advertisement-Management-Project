<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: Customer_Login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $customer_id = $_SESSION['customer_id'];
    $venue_id = $_POST['venue_id'];
    $payment_method = $_POST['paymentMethod'];
    $booking_date = $_POST['bookingDate'];
    $account_number = $_POST['Account_number'];
    $pin = $_POST['pin'];

    // Calculate the difference between booking date and current date
    $booking_date_timestamp = strtotime($booking_date);
    $current_date_timestamp = strtotime(date('Y-m-d'));
    $date_difference = $booking_date_timestamp - $current_date_timestamp;

    // Determine the description based on the date difference
    if ($date_difference < 0) {
        $description = "Time passed";
    } else {
        $description = "Coming";
    }

    // Database configuration
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "shop_db";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the booking date matches an existing date in the Schedule table
    $schedule_check_sql = "SELECT * FROM Schedule WHERE Venue_id = ? AND Schedule_date = ?";
    $stmt = $conn->prepare($schedule_check_sql);
    $stmt->bind_param("is", $venue_id, $booking_date);
    $stmt->execute();
    $schedule_result = $stmt->get_result();

    if ($schedule_result->num_rows > 0) {
        // Insert the booking record if date matches
        $sql = "INSERT INTO Booking (Customer_ID, Venue_id, Date, Payment_method, Account_number, Description) VALUES (?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($sql);
        $insert_stmt->bind_param("iissss", $customer_id, $venue_id, $booking_date, $payment_method, $account_number, $description);

        if ($insert_stmt->execute()) {
            // Redirect to booking history page after successful booking
            header("Location: BookingHistory.php");
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        $insert_stmt->close();
    } else {
        // Display the message as a JavaScript alert
        echo "<script>alert('The venue is not available on the selected date.'); window.location.href='Venue.php';</script>";
    }
    $stmt->close();
    $conn->close();
} else {
    // If the form is not submitted through POST method, redirect to the booking page
    header("Location: Venue.php");
    exit;
}
?>

















