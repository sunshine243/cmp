<?php
include('auth.php');
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quiz_id = $_POST['quiz_id'];
    $question_text = $_POST['question_text'];
    $score = $_POST['score'];
    $answers = $_POST['answers'];
    $correct_answer = $_POST['correct_answer'];

    // 插入问题并设置分数
    $sql = "INSERT INTO questions (quiz_id, question_text, score) VALUES ('$quiz_id', '$question_text', '$score')";
    if ($con->query($sql) === TRUE) {
        $question_id = $con->insert_id;

        // 插入答案
        foreach ($answers as $index => $answer) {
            $is_correct = ($index == $correct_answer) ? 1 : 0;
            $sql = "INSERT INTO answers (question_id, answer_text, is_correct) VALUES ('$question_id', '$answer', '$is_correct')";
            $con->query($sql);
        }

        // 根据管理员的选择跳转到相应页面
        if (isset($_POST['add_another'])) {
            header("Location: add_question_form.php?quiz_id=$quiz_id");
        } else {
            header("Location: dashboard.php");
        }
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}

$con->close();
?>
