<?php
session_start();
include ('auth.php');
include 'database.php'; 

if (!isset($_SESSION['id'])) {
    header("Location: admin_login.php");
    exit();
}
$status = "";

if(isset($_POST['new']) && $_POST['new'] == 1){
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $profile_desc = "";
    $profile_image = "";
    $password = stripslashes($_POST['password']);
    $password = md5(mysqli_real_escape_string($con,$password));
    $role = $_POST['role'];
    $ins_query="INSERT into teacher (`full_name`,`username`,`email`,`gender`,`profile_desc`,`profile_image`,`password`,`role`)values
    ('$full_name','$username','$email','$gender','$profile_desc','$profile_image','$password','$role')";
    
    // Execute the query
    $result = mysqli_query($con, $ins_query);
    
    if($result){
        $status = "New Teacher Inserted Successfully. </br></br><a href='ViewTeacherList.php'>View Teacher List</a>";
    } else {
        // Display error message if query fails
        $status = "Error: " . mysqli_error($con);
    }
}

$userId = $_SESSION['id'];
$query = "SELECT username, profile_image FROM admin WHERE id='$userId'";
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
<meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">        <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link href="../css/index.css" rel="stylesheet" />

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">  </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

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
    <title>Add Teacher</title>
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
                            <a href="dashboard.php">
                                <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                                <div class="title">Quiz</div>
                            </a>
                        </li>
                        <li>
                            <a href="../faq/IndexFaq.php">
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
                        <li>
                            <a href="admin_login.php">
                                <div class="icon"><i class="fas fa-sign-out-alt"></i></div>
                                <div class="logout_btn">Logout</div>
                            </a>
                        </li>
                    </ul>

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
                <h1>Add New Teacher</h1>
                <form method="POST">
                    <input type="hidden" name="new" value="1" />
                    <p><input type="text" name="full_name" placeholder="Enter Full Name" required /></p>
                    <p><input type="text" name="username" placeholder="Enter Username" required /></p>
                    <p><input type="email" name="email" placeholder="Enter Email" required /></p>
                    <p>
                        Gender:
                        <input type="radio" name="gender" value="Male" required> Male
                        <input type="radio" name="gender" value="Female" required> Female
                    </p>
                    <p><input type="password" name="password" placeholder="Enter Password" required /></p>
                    <p><input type="role" name="role" placeholder="Enter Role" required /></p>
                    <p><input type="submit" name="submit" value="Add" /></p>
                </form>
                <?php if (!empty($status)) { ?>
                    <p style="color:#008000;"><?php echo $status; ?></p>
                <?php } ?>
            </div>                    
            </div>
        </div>
</body>
</html>
