<?php
session_start();
include('auth.php');
include 'database.php';

// 权限验证
if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['teacher', 'admin'])) {
    $name = $_SESSION['role'];
} else {
    // 如果用户没有权限，重定向到登录页面
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['id'];
$query = "SELECT username, profile_image FROM $name WHERE id='$userId'";
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $username = $row['username']; // 从数据库中获取用户名
    if (isset($row['profile_image']) && !empty($row['profile_image'])) {
        $profileImage = $row['profile_image'];
    } else {
        $profileImage = '../image/default_profile_image.png';
    }
} else {
    // 如果查询失败或者没有找到用户，则使用默认图片并处理错误
    $username = "Unknown User";
    $profileImage = '../image/default_profile_image.png';  // 默认图片
}

// 处理创建Quiz的表单提交逻辑
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $time_limit = intval($_POST['time_limit']);

    // 表单验证
    if (empty($title)) {
        echo "Quiz title cannot be empty!";
    } elseif ($time_limit < 0) {
        echo "Time limit cannot be negative!";
    } else {
        // 使用预处理语句插入 Quiz 到数据库
        $stmt = $con->prepare("INSERT INTO quizzes (title, time_limit) VALUES (?, ?)");
        $stmt->bind_param("si", $title, $time_limit); // "si" 表示 title 是字符串，time_limit 是整数
        if ($stmt->execute()) {
            // 获取插入的 Quiz ID
            $quiz_id = $stmt->insert_id;
            // 成功创建 Quiz 后重定向到 Quiz Dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            // 输出错误信息
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>School Management Project - Admin Dashboard</title>
    <meta charset="UTF-8">
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
                        <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Image">
                    </div>
                    <div class="profile_data">
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
                    <a href="ViewSchoolList.php">School List</a>
                    <a href="ViewTeacherList.php">Teacher List</a>
                    <a href="ViewStudentList.php">Student List</a>
                </div>
            </div>

            <div class="content">
                <h1>Create a New Quiz</h1>
                <form action="create_quiz.php" method="post" class="form-group">
                    <label for="title">Quiz Title:</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                    
                    <label for="time_limit">Time Limit (in minutes):</label>
                    <input type="number" id="time_limit" name="time_limit" class="form-control" min="0" placeholder="0 for no limit" required>

                    <input type="submit" value="Create Quiz" class="btn btn-primary">
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
