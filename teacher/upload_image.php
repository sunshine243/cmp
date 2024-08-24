<?php
session_start();
include ('auth.php');
include 'database.php'; 

if (!isset($_SESSION['id'])) {
    header("Location: teacher_login.php");
    exit();
}

// $userId = $_SESSION['id'];
// $query = "SELECT username, profile_image FROM teacher WHERE id='$userId'";
// $result = mysqli_query($con, $query);

// if ($result && mysqli_num_rows($result) > 0) {
//     $row = mysqli_fetch_assoc($result);
//     $username = $row['username']; // 从数据库中获取用户名
//     if (isset($row['profile_image']) && !empty($row['profile_image'])) {
//         $profileImage = $row['profile_image'];
//     } else {
//         $profileImage = '../image/default_profile_image.png';
//     }
// } else {
//     // 如果查询失败或者没有找到用户，则使用默认图片并处理错误
//     $username = "Unknown User";
//     $profileImage = '../image/default_profile_image.png';  //default image
// }
// ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Setting</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link href="../css/index.css" rel="stylesheet" />
    <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./asset/summernote-lite.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./asset/summernote-lite.js"></script>
    <script src="./bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
    <style>
        :root {
            --bs-success-rgb: 71, 222, 152 !important;
        }

        html,
        body {
            height: 100%;
            width: 100%;
            font-family: Apple Chancery, cursive;
        }

        .btn-info.text-light:hover,
        .btn-info.text-light:focus {
            background: #000;
        }
    </style>

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

</head>

<body>
      <!-- Display current profile image -->
      <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="profile_image" id="profileImage" style="width: 200px; height: 200px; object-fit: cover;">

<!-- Form to upload new profile image -->
<form action="../teacher/upload.php" method="post" enctype="multipart/form-data">
    <label for="fileToUpload">Select image to upload:</label>
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>
</body>

</html>