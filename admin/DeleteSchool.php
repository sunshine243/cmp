<?php
require('database.php');
$id=$_GET['id'];
$query = "DELETE FROM school WHERE id=$id";
$result = mysqli_query($con,$query) or die ( mysqli_error($con));
header("Location: ViewSchoolList.php");
exit();
?>