<?php
session_start();
include('auth.php');
include 'database.php';

$quiz_id = $_GET['id'];

// 如果表单被提交，更新 Quiz 信息
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $time_limit = $_POST['time_limit'];

    // 更新 Quiz 的 SQL 语句
    $sql = "UPDATE quizzes SET title = '$title', time_limit = '$time_limit' WHERE id = $quiz_id";
    if ($con->query($sql) === TRUE) {
        echo "<script>alert('Quiz updated successfully'); window.location.href = 'dashboard.php';</script>";
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
} else {
    // 获取当前 Quiz 的信息
    $sql = "SELECT title, time_limit FROM quizzes WHERE id = $quiz_id";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
        $quiz = $result->fetch_assoc();
    } else {
        echo "<script>alert('Quiz not found'); window.location.href = 'dashboard.php';</script>";
        exit;
    }
}

// 获取当前用户的头像和用户名
$userId = $_SESSION['id'];
$role = $_SESSION['role']; // 获取用户角色
$query = "SELECT username, profile_image FROM " . $role . " WHERE id='$userId'";
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $username = $row['username']; // 从数据库中获取用户名
    if (isset($row['profile_image']) && !empty($row['profile_image'])) {
        $profileImage = $row['profile_image']; // 用户的头像
    } else {
        $profileImage = '../image/default_profile_image.png'; // 默认头像
    }
} else {
    // 如果查询失败或者没有找到用户信息，则使用默认头像
    $username = "Unknown User";
    $profileImage = '../image/default_profile_image.png';  // 默认头像
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

        html, body {
            height: 100%;
            width: 100%;
            font-family: Apple Chancery, cursive;
        }

        .btn-info.text-light:hover, .btn-info.text-light:focus {
            background: #000;
        }

        form {
            margin-top: 20px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .form-control {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="sidebar">
            <div class="bg_shadow"></div>
            <div class="sidebar_inner">
                <div class="close">
                    <i class="fas fa-times"></i>
                </div>

                <div class="profile_info">
                    <div class="profile_img">
                        <!-- 显示用户头像 -->
                        <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Image">
                    </div>
                    <div class="profile_data">
                        <!-- 显示用户名 -->
                        <p class="name"><?php echo htmlspecialchars($username); ?></p>
                        <span><i class="fas fa-map-marker-alt"></i>Kampar, Perak</span>
                    </div>
                </div>

                <ul class="siderbar_menu">
                    <li><a href="admin_index.php"><div class="icon"><i class="fas fa-home"></i></div><div class="title">Home</div></a></li>
                    <li><a href="dashboard.php"><div class="icon"><i class="fas fa-clipboard-list"></i></div><div class="title">Quiz Dashboard</div></a></li>
                    <li><a href="upload_image.php"><div class="icon"><i class="fas fa-calendar-alt"></i></div><div class="title">Profile</div></a></li>
                    <li><a href="admin_login.php"><div class="icon"><i class="fas fa-sign-out-alt"></i></div><div class="logout_btn">Logout</div></a></li>
                </ul>
            </div>
        </div>

        <div class="main_container">
            <div class="navbar">
                <div class="hamburger"><i class="fas fa-bars"></i></div>
                <div class="logo">
                    <a href="teacher_ViewSchool.php">School List</a>
                    <a href="teacher_list.php">Teacher List</a>
                    <a href="student_list.php">Student List</a>
                </div>
            </div>

            <div class="content">
                <h1>Edit Quiz</h1>
                <form action="edit_quiz.php?id=<?php echo $quiz_id; ?>" method="post" class="form-group">
                    <label for="title">Quiz Title:</label>
                    <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($quiz['title']); ?>" required>
                    
                    <label for="time_limit">Time Limit (in minutes):</label>
                    <input type="number" id="time_limit" name="time_limit" class="form-control" value="<?php echo htmlspecialchars($quiz['time_limit']); ?>" min="0" required>

                    <input type="submit" value="Update Quiz" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $(".arrow").click(function() {
                $(this).find("i").toggleClass("fa-chevron-down fa-chevron-up");
                $(this).siblings(".accordion").slideToggle();
            });

            $(".siderbar_menu li").click(function() {
                var isActive = $(this).hasClass("active");
                $(".siderbar_menu li").removeClass("active");
                $(".accordion").slideUp();
                $(".arrow i").removeClass("fa-chevron-up").addClass("fa-chevron-down");

                if (!isActive) {
                    $(this).addClass("active");
                    $(this).find(".accordion").slideDown();
                    $(this).find(".arrow i").toggleClass("fa-chevron-down fa-chevron-up");
                }
            });

            $(".hamburger").click(function() {
                $(".wrapper").addClass("active");
            });

            $(".close, .bg_shadow").click(function() {
                $(".wrapper").removeClass("active");
            });
        });
    </script>
</body>

</html>
