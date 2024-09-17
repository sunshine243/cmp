<?php
require('database.php');
$id=$_GET['id'];
$query = "DELETE FROM student WHERE id=$id";
$result = mysqli_query($con,$query) or die ( mysqli_error($con));
header("Location: ViewStudentList.php");
exit();
?>