<?php
include '../database.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];  // Assuming the student ID is stored in session as 'id'
    $quiz_id = $_POST['quiz_id'];
    $score = 0;
    $total_score = 0;

    // Get all questions and their respective scores
    $sql = "SELECT id, score FROM questions WHERE quiz_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $question_id = $row['id'];
        $question_score = $row['score'];
        $total_score += $question_score;

        // Check if the user selected an answer for the current question
        if (isset($_POST["answer_$question_id"])) {
            $user_answer = $_POST["answer_$question_id"];

            // Get the correct answer for the question
            $sql_correct = "SELECT id FROM answers WHERE question_id = ? AND is_correct = 1";
            $stmt_correct = $con->prepare($sql_correct);
            $stmt_correct->bind_param("i", $question_id);
            $stmt_correct->execute();
            $result_correct = $stmt_correct->get_result();
            $correct_answer = $result_correct->fetch_assoc()['id'];

            // Compare the user's answer with the correct answer
            if ($user_answer == $correct_answer) {
                $score += $question_score;
            }
        }
    }

    // Save the user's score in the 'user_scores' table
    $sql = "INSERT INTO user_scores (username, quiz_id, score, total_score) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("siii", $username, $quiz_id, $score, $total_score);

    if ($stmt->execute()) {
        echo "<script>alert('Your Score: $score / $total_score'); window.location.href = 'quiz_home.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
}
?>
