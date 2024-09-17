<?php
session_start();
include('auth.php');
include 'database.php';

if (isset($_SESSION['role']) && $_SESSION['role'] == "teacher") {
    $name = $_SESSION['role'];
} elseif (isset($_SESSION['role']) && $_SESSION['role'] == "admin") {
    $name = $_SESSION['role'];
}

$userId = $_SESSION['id'];
$query = "SELECT username, profile_image FROM " . $name . " WHERE id='$userId'";
$result = mysqli_query($con, $query);

// Retrieve the school_name from the URL
if (isset($_GET['school_name'])) {
    $school_name = htmlspecialchars($_GET['school_name']);
    // Now you can use $school_name to identify the school
    //echo "school_name :" . $school_name;
} else {
    // Handle the case where school_name is not provided
    // echo "School name is not specified.";
}

// Retrieve the school_name from the URL
if (isset($_GET['post'])) {
    $post = htmlspecialchars($_GET['post']);
    // Now you can use $school_name to identify the school
    //echo "school_name: " . $post;
} else {
    // Handle the case where school_name is not provided
    // echo "School name is not specified.";
}

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $username = $row['username']; // 从数据库中获取用户名
    if (isset($row['profile_image']) && !empty($row['profile_image'])) {
        if ($name == 'admin') {
            $profileImage = str_replace('../', './', $row['profile_image']);
        } else if ($name == 'teacher') {
            $profileImage = str_replace('../', './', $row['profile_image']);
        }
    } else {
        $profileImage = '../image/default_profile_image.png';
    }
} else {
    // 如果查询失败或者没有找到用户，则使用默认图片并处理错误
    $username = "Unknown User";
    $profileImage = '../image/default_profile_image.png';  //default image
}

?>

<!DOCTYPE html>

<html>

<head>
    <title>School Management Project</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./asset/summernote-lite.css">
    <script src="./asset/summernote-lite.js"></script>
    <script src="./bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
    <link href="./css/index.css" rel="stylesheet" />

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

    <script>
        $(document).ready(function() {
            $(".arrow").click(function() {
                $(this).find("i").toggleClass("fa-chevron-down fa-chevron-up");
                $(this).siblings(".accordion").slideToggle();
            });

            $(".siderbar_menu li").click(function() {
                // Check if the current item is active
                var isActive = $(this).hasClass("active");

                // Remove 'active' class from all items
                $(".siderbar_menu li").removeClass("active");
                // Close all accordions
                $(".accordion").slideUp();
                // Change all arrow icons to 'fa-chevron-down'
                $(".arrow i").removeClass("fa-chevron-up").addClass("fa-chevron-down");

                // If the clicked item was not active, make it active and open its accordion
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

</head>

<body>
    <form id="form1" runat="server">

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
                        <li>
                            <a href="admin_index.php">
                                <div class="icon"><i class="fas fa-home"></i></div>
                                <div class="title">Home</div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="icon"><i class="fas fa-hotel"></i></div>
                                <div class="title">School</div>
                                <div class="arrow"><i class="fas fa-chevron-down"></i></div>
                            </a>
                            <ul class="accordion">
                                <li><a href="AddSchool.php" class="active"><i class="fas fa-user-plus pr-1"></i>Add School</a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="#">
                                <div class="icon"><i class="fas fa-user-tie"></i></div>
                                <div class="title">Teachers</div>
                                <div class="arrow"><i class="fas fa-chevron-down"></i></div>
                            </a>
                            <ul class="accordion">
                                <li><a href="AddTeacher.php" class="active"><i class="fas fa-user-plus pr-1"></i>Add Teachers</a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="#">
                                <div class="icon"><i class="fas fa-user-graduate"></i></div>
                                <div class="title">Students</div>
                                <div class="arrow"><i class="fas fa-chevron-down"></i></div>
                            </a>
                            <ul class="accordion">
                                <li><a href="AddStudent.php" class="active"><i class="fas fa-users pr-1"></i>Add Students</a></li>
                            </ul>

                        </li>
                        <li>
                            <a href="quiz.php">
                                <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                                <div class="title">Quiz</div>
                            </a>
                        </li>
                        <li>
                            <a href="IndexFaq.php">
                                <div class="icon"><i class="fas fa-info-circle"></i></div>
                                <div class="title">FAQ</div>
                            </a>
                        </li>
                        <li>
                            <a href="upload_image.php">
                                <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                                <div class="title">Profile</div>
                            </a>
                        </li>
                    </ul>
                    <div class="logout_btn">
                        <?php
                        if (isset($_SESSION['username'])) {
                            echo '<a href="admin_login.php">Logout</a>';
                        } else {
                            // 如果没有设置用户名会话，则显示登录按钮或其他登录相关的内容
                            // 这里可以根据需要添加适当的登录按钮或链接
                            echo '<a href="admin_login.php">Logout</a>';
                        }
                        ?>
                    </div>

                </div>
            </div>

            <div class="main_container">
                <div class="navbar">
                    <div class="hamburger">
                        <i class="fas fa-bars"></i>
                    </div>
                    <div class="logo">
                        <a href="ViewSchoolList.php">School List</a>
                        <a href="ViewTeacherList.php">Teacher List</a>
                        <a href="ViewStudentList.php">Student List</a>
                    </div>
                </div>

                <div class="content">
                    <h3>Content👇🏻👇🏻👇🏻</h3>

                    <?php
                    if (isset($_SESSION['msg'])):
                    ?>
                        <div class="alert alert-<?php echo $_SESSION['msg']['type'] ?>">
                            <div class="d-flex w-100">
                                <div class="col-11"><?php echo $_SESSION['msg']['text'] ?></div>
                                <div class="col-1 d-flex justify-content-end align-items-center">
                                    <button class="btn-close" onclick="$(this).closest('.alert').hide('slow')"></button>
                                </div>
                            </div>
                        </div>
                    <?php
                        unset($_SESSION['msg']);
                    endif;
                    ?>
                    <div class="col-12 my-2">
                        <a href="<?php echo './schools/' . urlencode($school_name) . '/' . strtolower($school_name) . '.php'; ?>" class="btn btn-info text-light text-decoration-none">
                            Back to List
                        </a>
                    </div>

                    <div class="content">
                        <?php
                        if (isset($_GET['post']) && is_file("./schools/" . $school_name . "/post/{$_GET['post']}")) {
                            // Fetch and display the content of the post
                            echo file_get_contents("./schools/" . $school_name . "/post/{$_GET['post']}");
                        } else {
                            // Handle the case where the post is not found
                            echo "<center>Unknown Page Content.</center>";
                        }
                        ?>
                    </div>
                </div>
            </div>
    </form>
</body>

</html>