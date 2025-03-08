<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "shop_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['type']) && isset($data['id'])) {
    $type = $data['type'];
    $id = $data['id'];
    $table = $type === 'question' ? 'VenueQuestions' : 'Booking';
    $idField = $type === 'question' ? 'ID' : 'Booking_ID';

    $update_sql = "UPDATE $table SET IsRead = TRUE WHERE $idField = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "Invalid input"]);
}

$conn->close();
?>


