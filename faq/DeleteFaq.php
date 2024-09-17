<?php
include('../auth.php');
include '../database.php';

// Check if FAQ exists
$id = $_REQUEST["id"];
$sql = "SELECT * FROM faqs WHERE id = '$id'";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) === 0) {
    die("FAQ not found");
}

// Delete from database
$sql = "DELETE FROM faqs WHERE id = '$id'";
if (mysqli_query($con, $sql)) {
    // Redirect to previous page
    header("Location: " . $_SERVER["HTTP_REFERER"]);
    exit();
} else {
    echo "Error: " . mysqli_error($con);
}

// Close connection
mysqli_close($con);
?>
