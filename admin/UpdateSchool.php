<?php
session_start();
include('auth.php');
include 'database.php';

if (!isset($_SESSION['id'])) {
    header("Location: admin_login.php");
    exit();
}
$status = "";

$id = $_REQUEST['id'];
$query = "SELECT * FROM school WHERE id='" . $id . "'";
$result = mysqli_query($con, $query) or die(mysqli_error($con));
$schoolRow = mysqli_fetch_assoc($result); // 使用 $schoolRow 存储学校的查询结果

$userId = $_SESSION['id'];
$query = "SELECT username, profile_image FROM admin WHERE id='$userId'";
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $userRow = mysqli_fetch_assoc($result); // 使用 $userRow 存储用户的查询结果
    $username = $userRow['username']; // 从数据库中获取用户名
    if (isset($userRow['profile_image']) && !empty($userRow['profile_image'])) {
        $profileImage = $userRow['profile_image'];
    } else {
        $profileImage = '../image/default_profile_image.png';
    }
} else {
    // 如果查询失败或者没有找到用户，则使用默认图片并处理错误
    $username = "Unknown User";
    $profileImage = '../image/default_profile_image.png';  // 默认图片
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link href="../css/index.css" rel="stylesheet" />

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

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
    <title>Update School List</title>
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
                        <a href="quiz.php">
                            <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                            <div class="title">Quiz</div>
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
                        echo '<a href="logout.php">Logout</a>';
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
                    <a href="admin_index.php">Home Page</a> |
                    <a href="ViewSchoolList.php">School List</a> |
                    <a href="admin_login.php">Logout</a>
                </div>
            </div>
            <div class="content">
                <h1>Update School List</h1>
                <?php
$status = "";
if (isset($_POST['new']) && $_POST['new'] == 1) {
    $id = $_POST['id']; // 确保从POST中获取ID
    $school_name = $_POST['school_name'];

    // 检查 ID 是否存在
    if (!empty($id)) {
        // 获取当前学校的信息，以便后续重命名文件
        $query = "SELECT * FROM school WHERE id='" . mysqli_real_escape_string($con, $id) . "'";
        $result = mysqli_query($con, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $old_school_name = $row['school_name'];
            $old_file_name = strtolower(str_replace(' ', '_', $old_school_name)) . '.php';
            $new_file_name = strtolower(str_replace(' ', '_', $school_name)) . '.php';
            $old_file_path = '../schools/' . $old_file_name;
            $new_file_path = '../schools/' . $new_file_name;

            // // Debugging: Output file paths
            // echo "<p>Old file path: $old_file_path</p>";
            // echo "<p>New file path: $new_file_path</p>";

            // 更新数据库记录
            $update = "UPDATE school SET school_name='" . mysqli_real_escape_string($con, $school_name) . "' WHERE id='" . mysqli_real_escape_string($con, $id) . "'";
            if (mysqli_query($con, $update)) {
                // 重命名 PHP 文件
                if (file_exists($old_file_path)) {
                    if (rename($old_file_path, $new_file_path)) {
                        $status = "School List Updated Successfully. </br></br><a href='ViewSchoolList.php'>View Updated Record</a>";
                    } else {
                        $status = "Error renaming file.";
                    }
                } else {
                    $status = "Error: The old file does not exist.";
                }
            } else {
                $status = "Error updating record: " . mysqli_error($con);
            }
        } else {
            $status = "Error: No school found with the provided ID.";
        }
    } else {
        $status = "Invalid School ID.";
    }

    echo '<p style="color:#008000;">' . $status . '</p>';
} else {
    if (isset($_GET['id'])) {
        $id = $_GET['id']; // 从GET中获取ID

        // 获取当前学校信息
        $query = "SELECT * FROM school WHERE id='" . mysqli_real_escape_string($con, $id) . "'";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
        } else {
            echo "<p style='color:#FF0000;'>No school found with the provided ID.</p>";
            exit;
        }
    } else {
        echo "<p style='color:#FF0000;'>No school ID provided.</p>";
        exit;
    }
?>

    <form name="form" method="post" action="">
        <input type="hidden" name="new" value="1" />
        <input name="id" type="hidden" value="<?php echo $row['id']; ?>" />
        <p><input type="text" name="school_name" placeholder="Update School Name" required value="<?php echo $row['school_name']; ?>" /></p>
        <p><input name="submit" type="submit" value="Update" /></p>
    </form>

<?php } ?>

            </div>

        </div>
    </div>
</body>

</html>