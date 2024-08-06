<?php
session_start();
include('database.php');

// Fetch the current user's profile image from the database
$userId = $_SESSION['id'];
$query = "SELECT profile_image FROM admin WHERE id='$userId'";
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $profileImage = isset($row['profile_image']) ? $row['profile_image'] : '../image/default_profile_image.png';
} else {
    $profileImage = '../image/default_profile_image.png'; // default image
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <!-- Display current profile image -->
    <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="profile_image" id="profileImage" style="width: 200px; height: 200px; object-fit: cover;">

    <!-- Form to upload new profile image -->
    <form action="../admin/upload.php" method="post" enctype="multipart/form-data">
        <label for="fileToUpload">Select image to upload:</label>
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit">
    </form>
</body>
</html>
