<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "shop_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$images = [];
$venue = null;
$reviews = [];
$questions = [];

if (isset($_GET['Venue_id'])) {
    $Venue_id = $_GET['Venue_id'];

    // Retrieve venue details including the image paths
    $venue_sql = "SELECT * FROM Venue WHERE Venue_id = $Venue_id";
    $venue_result = $conn->query($venue_sql);

    if ($venue_result->num_rows > 0) {
        $venue = $venue_result->fetch_assoc();
        $venue_images = explode(",", $venue['venue_image']);
    }

    // Retrieve reviews for the venue
    $reviews_sql = "SELECT r.Review, r.Rating, u.Username FROM Reviews r
                    LEFT JOIN Customer_signup u ON r.Customer_id = u.ID
                    WHERE r.Venue_id = $Venue_id";
    $reviews_result = $conn->query($reviews_sql);

    if ($reviews_result->num_rows > 0) {
        while ($row = $reviews_result->fetch_assoc()) {
            $reviews[] = $row;
        }
    }

    // Retrieve questions and answers for the venue
    $questions_sql = "SELECT q.Question, q.Answer, u.Username FROM VenueQuestions q
                      LEFT JOIN Customer_signup u ON q.Customer_id = u.ID
                      WHERE q.Venue_id = $Venue_id";
    $questions_result = $conn->query($questions_sql);

    if ($questions_result->num_rows > 0) {
        while ($row = $questions_result->fetch_assoc()) {
            $questions[] = $row;
        }
    }
}

// Handle form submission for asking a question
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['question'])) {
    session_start();
    $customer_id = $_SESSION['customer_id'];
    $question = $_POST['question'];

    $stmt = $conn->prepare("INSERT INTO VenueQuestions (Venue_id, Customer_id, Question) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $Venue_id, $customer_id, $question);
    $stmt->execute();
    $stmt->close();

    // Send email to admin
    $admin_email = "nasratj35@gmail.com";
    $customer_email = $_SESSION['customer_email'];
    $subject = "New Question About Venue ID: $Venue_id";
    $message = "Customer (ID: $customer_id) asked: $question";
    $headers = "From: $customer_email";

    mail($admin_email, $subject, $message, $headers);

    // Redirect to avoid resubmission
    header("Location: Venue_Details.php?Venue_id=$Venue_id");
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venue Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-image: url('image6.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .review-card {
            margin-bottom: 20px;
        }
        .image-container img {
            margin-bottom: 10px;
            max-width: 100%;
            height: auto;
        }
        .question-card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="my-4">Venue Details</h1>

    <?php if ($venue): ?>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title"><?php echo $venue['Vanue_Name']; ?></h3>
                        <p class="card-text">Location: <?php echo $venue['Location']; ?></p>
                        <p class="card-text">Price: <?php echo $venue['Price']; ?></p>
                        <?php if (!empty($venue_images)): ?>
                            <div class="image-container">
                                <?php foreach ($venue_images as $image): ?>
                                    <img src="<?php echo $image; ?>" alt="Venue Image" class="img-fluid mb-3">
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h3>Customer Reviews</h3>
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="card review-card">
                            <div class="card-body">
                                <h5 class="card-title">Username: <?php echo $review['Username']; ?></h5>
                                <p class="card-text">Review: <?php echo $review['Review']; ?></p>
                                <p class="card-text">Rating: <?php echo $review['Rating']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No reviews yet.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <h3>Customer Questions</h3>
                <?php if (!empty($questions)): ?>
                    <?php foreach ($questions as $question): ?>
                        <div class="card question-card">
                            <div class="card-body">
                                <h5 class="card-title">Question by <?php echo $question['Username']; ?></h5>
                                <p class="card-text"><?php echo $question['Question']; ?></p>
                                <?php if ($question['Answer']): ?>
                                    <p class="card-text"><strong>Answer:</strong> <?php echo $question['Answer']; ?></p>
                                <?php else: ?>
                                    <p class="card-text"><strong>Answer:</strong> Awaiting answer</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No questions yet.</p>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <h3>Ask a Question</h3>
                <form method="POST">
                    <div class="mb-3">
                        <textarea class="form-control" name="question" rows="3" placeholder="Write your question here" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Question</button>
                </form>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12 text-center">
                <a href="schedule.php?Venue_id=<?php echo $Venue_id; ?>" class="btn btn-primary schedule-btn">Schedule</a>
            </div>
        </div>
    <?php else: ?>
        <p>Venue details not found.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
























