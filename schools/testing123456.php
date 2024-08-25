<?php
session_start();
include ('../admin/auth.php');
include '../admin/database.php';

if (isset($_SESSION['role']) && $_SESSION['role'] == "teacher") {
    $name = $_SESSION['role'];
} elseif (isset($_SESSION['role']) && $_SESSION['role'] == "admin") {
    $name = $_SESSION['role'];
}

if (!isset($_SESSION['id'])) {
    header("Location: ../".  ."_login.php");
    exit();
}


$userId = $_SESSION['id'];
$query = "SELECT username, profile_image FROM" .  . " WHERE id='$userId'";
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
    $profileImage = '../image/default_profile_image.png';  //default image
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>testing123456</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/index.css" rel="stylesheet" />
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../asset/summernote-lite.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../asset/summernote-lite.js"></script>
    <script src="../bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
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
        .btn-info.text-light:hover,.btn-info.text-light:focus{
            background: #000;
        }
    </style>

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
            $(".arrow").click(function () {
                $(this).find("i").toggleClass("fa-chevron-down fa-chevron-up");
                $(this).siblings(".accordion").slideToggle();
            });

            $(".siderbar_menu li").click(function () {
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

            $(".hamburger").click(function () {
                $(".wrapper").addClass("active");
            });

            $(".close, .bg_shadow").click(function () {
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
                            <a href="../admin/admin_index.php">
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
                                <li><a href="../AddSchool.php" class="active"><i class="fas fa-user-plus pr-1"></i>Add School</a></li>
                            </ul>
                        </li>
                       
                        <li>
                            <a href="#">
                                <div class="icon"><i class="fas fa-user-tie"></i></div>
                                <div class="title">Teachers</div>
                                <div class="arrow"><i class="fas fa-chevron-down"></i></div>
                            </a>
                            <ul class="accordion">
                                <li><a href="../AddTeacher.php" class="active"><i class="fas fa-user-plus pr-1"></i>Add Teachers</a></li>
                                </ul>
                        </li>

                        <li>
                            <a href="#">
                                <div class="icon"><i class="fas fa-user-graduate"></i></div>
                                <div class="title">Students</div>
                                <div class="arrow"><i class="fas fa-chevron-down"></i></div>
                            </a>
                            <ul class="accordion">
                                <li><a href="../AddStudent.php" class="active"><i class="fas fa-users pr-1"></i>Add Students</a></li>
                            </ul>

                        </li>
                        <li>
                            <a href="../quiz.php">
                                <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                                <div class="title">Quiz</div>
                            </a>
                        </li>
                        <li>
                            <a href="../IndexFaq.php">
                                <div class="icon"><i class="fas fa-info-circle"></i></div>
                                <div class="title">FAQ</div>
                            </a>
                        </li>
                        <li>
                            <a href="../upload_image.php">
                                <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                                <div class="title">Profile</div>
                            </a>
                        </li>
                    </ul>
                    <div class="logout_btn">
                        <?php
                        if (isset($_SESSION['username'])) {
                            echo '<a href="../admin_login.php">Logout</a>';
                        } else {
                            echo '<a href="../admin_login.php">Logout</a>';
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
                        <a href="../ViewSchoolList.php">School List</a>
                        <a href="../ViewTeacherList.php">Teacher List</a>
                        <a href="../ViewStudentList.php">Student List</a>
                    </div>
                </div>  

                <div class="content">
                    <h1>Welcome to testing123456!</h1>
                    <p>This is the auto-generated page for testing123456.</p>

                    <?php if ($_SESSION['role'] == "admin"): ?>
                    <a href="../manage_post.php" class="btn btn-primary">Add Post</a>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </form>
</body>
</html>