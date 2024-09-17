<?php

session_start();
include('database.php');

$target_dir = "../uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Function to display the retry button
function displayRetryButton() {
    echo '<br><form action="upload_image.php" method="get"><input type="submit" value="Retry Upload"></form>';
}

// Check if image file is an actual image or fake image
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
        displayRetryButton();
    }
}

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
    displayRetryButton();
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 10000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
    displayRetryButton();
}

// Allow certain file formats
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
    displayRetryButton();
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
    displayRetryButton();
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars(basename($_FILES["fileToUpload"]["name"])). " has been uploaded.";
        
        // // Get current user's old profile image path
        $userId = $_SESSION['id'];
        $query = "SELECT profile_image FROM admin WHERE id='$userId'";
        $result = mysqli_query($con, $query);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $oldProfileImage = $row['profile_image'];

            // Delete old profile image if it's not the default image
            if ($oldProfileImage && $oldProfileImage != '../image/default_profile_image.png') {
                if (file_exists($oldProfileImage)) {
                    unlink($oldProfileImage);
                }
            }
        }

        // Update the database with the new profile image path
        $userId = $_SESSION['id'];
        $filePath = mysqli_real_escape_string($con, $target_file);
        $updateQuery = "UPDATE admin SET profile_image='$filePath' WHERE id='$userId'";
        if (mysqli_query($con, $updateQuery)) {
            echo "Profile image updated successfully.";
            echo "<br><a href='admin_index.php'>Go back to Home</a>";
        } else {
            echo "Sorry, there was an error updating your profile image: " . mysqli_error($con);
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
