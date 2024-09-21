<?php
session_start();
include('auth.php');
include 'database.php';

// 检查用户角色，确保用户已经登录并且具有正确的权限
if (isset($_SESSION['role']) && $_SESSION['role'] == "teacher") {
    $name = $_SESSION['role'];
} elseif (isset($_SESSION['role']) && $_SESSION['role'] == "admin") {
    $name = $_SESSION['role'];
} else {
    header('Location: admin_login.php');  // 如果用户没有权限，重定向到登录页面
    exit;
}

// 获取用户信息
$userId = $_SESSION['id'];
$query = "SELECT username, profile_image FROM " . $name . " WHERE id='$userId'";
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $username = $row['username'];
    if (isset($row['profile_image']) && !empty($row['profile_image'])) {
        $profileImage = $row['profile_image'];
    } else {
        $profileImage = '../image/default_profile_image.png';
    }
} else {
    $username = "Unknown User";
    $profileImage = '../image/default_profile_image.png';
}

// 查询所有的 Quiz
$sql = "SELECT id, title FROM quizzes";
$result = $con->query($sql); // 使用正确的数据库连接变量 $con
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Question</title>
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
                    <li><a href="#"><div class="icon"><i class="fas fa-hotel"></i></div><div class="title">School</div></a></li>
                    <li><a href="#"><div class="icon"><i class="fas fa-user-tie"></i></div><div class="title">Teachers</div></a></li>
                    <li><a href="#"><div class="icon"><i class="fas fa-user-graduate"></i></div><div class="title">Students</div></a></li>
                    <li><a href="dashboard.php"><div class="icon"><i class="fas fa-clipboard-list"></i></div><div class="title">Quiz</div></a></li>
                    <li><a href="../faq/IndexFaq.php"><div class="icon"><i class="fas fa-info-circle"></i></div><div class="title">FAQ</div></a></li>
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
                <h1>Select a Quiz to Add Questions</h1>
                <form action="add_question_form.php" method="get" class="form-group">
                    <label for="quiz">Choose a Quiz:</label>
                    <select id="quiz" name="quiz_id" class="form-control" required>
                        <?php
                        if ($result->num_rows > 0) {
                            // 输出每一行数据
                            while($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row["id"] . "'>" . $row["title"] . "</option>";
                            }
                        } else {
                            echo "<option value=''>No quizzes available</option>";
                        }
                        ?>
                    </select>
                    <br>
                    <input type="submit" value="Add Questions" class="btn btn-primary">
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
