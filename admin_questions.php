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

// Handle the form submission to answer a question
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['question_id']) && isset($_POST['answer'])) {
    $question_id = $_POST['question_id'];
    $answer = $_POST['answer'];

    $stmt = $conn->prepare("UPDATE VenueQuestions SET Answer = ?, IsRead = TRUE WHERE ID = ?");
    $stmt->bind_param("si", $answer, $question_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to avoid resubmission
    header("Location: admin_questions.php");
    exit;
}

// Retrieve all questions
$questions_sql = "SELECT q.ID, q.Question, q.Answer, q.Venue_id, q.IsRead, u.Username 
                  FROM VenueQuestions q
                  LEFT JOIN Customer_signup u ON q.Customer_id = u.ID";
$questions_result = $conn->query($questions_sql);

// Mark all questions as read
$conn->query("UPDATE VenueQuestions SET IsRead = TRUE WHERE IsRead = FALSE");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Customer Questions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-image: url('image40.png');
            background-color: #f0f0f0; /* Fallback color in case the background image doesn't load */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        </style>
</head>
<body>
<div class="container">
    <h1 class="my-4">Customer Questions</h1>

    <?php if ($questions_result->num_rows > 0): ?>
        <?php while ($row = $questions_result->fetch_assoc()): ?>
            <div class="card mb-4 <?php echo !$row['IsRead'] ? 'bg-light' : ''; ?>">
                <div class="card-body">
                    <h5 class="card-title">Question by <?php echo $row['Username']; ?> (Venue ID: <?php echo $row['Venue_id']; ?>)</h5>
                    <p class="card-text"><?php echo $row['Question']; ?></p>
                    <?php if ($row['Answer']): ?>
                        <p class="card-text"><strong>Answer:</strong> <?php echo $row['Answer']; ?></p>
                    <?php else: ?>
                        <form method="POST">
                            <input type="hidden" name="question_id" value="<?php echo $row['ID']; ?>">
                            <div class="mb-3">
                                <textarea class="form-control" name="answer" rows="3" placeholder="Write your answer here" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Answer</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No questions found.</p>
    <?php endif; ?>
</div>

<script>
        document.addEventListener("DOMContentLoaded", function() {
            var highlightedId = <?php echo json_encode($highlightedId); ?>;
            if (highlightedId) {
                var element = document.getElementById('question-' + highlightedId);
                if (element) {
                    element.scrollIntoView();
                }
            }
        });
    </script>
    </body>
</html>

