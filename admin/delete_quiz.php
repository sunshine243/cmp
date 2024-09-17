<?php
include('auth.php');
include 'database.php';

$quiz_id = $_GET['id'];

// 删除Quiz和相关问题
$sql = "DELETE FROM quizzes WHERE id = $quiz_id";

if ($con->query($sql) === TRUE) {
    echo "<script>alert('Quiz deleted successfully'); window.location.href = 'dashboard.php';</script>";
} else {
    echo "Error: " . $sql . "<br>" . $con->error;
}

$con->close();
?>
