<?php
include '../database.php';

// 获取所有的Quiz
$sql = "SELECT id, title FROM quizzes";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take a Quiz</title>
</head>
<body>
    <h1>Select a Quiz to Take</h1>
    <form action="quiz_questions.php" method="get">
        <label for="quiz">Choose a Quiz:</label>
        <select id="quiz" name="quiz_id" required>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["id"] . "'>" . $row["title"] . "</option>";
                }
            } else {
                echo "<option value=''>No quizzes available</option>";
            }
            ?>
        </select>
        <br><br>
        <input type="submit" value="Start Quiz">
    </form>
</body>
</html>
