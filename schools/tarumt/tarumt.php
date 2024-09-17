<?php
session_start();
include('../../admin/auth.php');
include '../../admin/database.php';

if (isset($_SESSION['role']) && $_SESSION['role'] == "teacher") {
    $name = $_SESSION['role'];
} elseif (isset($_SESSION['role']) && $_SESSION['role'] == "admin") {
    $name = $_SESSION['role'];
}

if (!isset($_SESSION['id'])) {
    header("Location: ../" . $name . "_login.php");
    exit();
}


$userId = $_SESSION['id'];
$query = "SELECT username, profile_image FROM " . $name . " WHERE id='$userId'";
$result = mysqli_query($con, $query);
$school_name = "tarumt";
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $username = $row['username']; // 从数据库中获取用户名
    if (isset($row['profile_image']) && !empty($row['profile_image'])) {
        $profileImage = '../' . $row['profile_image'];
    } else {
        $profileImage = '../../image/default_profile_image.png';
    }
} else {
    // 如果查询失败或者没有找到用户，则使用默认图片并处理错误
    $username = "Unknown User";
    $profileImage = '../../image/default_profile_image.png';  //default image
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>tarumt</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link href="../../css/index.css" rel="stylesheet" />
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../asset/summernote-lite.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../../asset/summernote-lite.js"></script>
    <script src="../../bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
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

        .card-content {
            width: 96%;
        }
    </style>

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>

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
                            <a href="../../admin/admin_index.php">
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
                                <li><a href="../../admin/AddSchool.php" class="active"><i class="fas fa-user-plus pr-1"></i>Add School</a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="#">
                                <div class="icon"><i class="fas fa-user-tie"></i></div>
                                <div class="title">Teachers</div>
                                <div class="arrow"><i class="fas fa-chevron-down"></i></div>
                            </a>
                            <ul class="accordion">
                                <li><a href="../../admin/AddTeacher.php" class="active"><i class="fas fa-user-plus pr-1"></i>Add Teachers</a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="#">
                                <div class="icon"><i class="fas fa-user-graduate"></i></div>
                                <div class="title">Students</div>
                                <div class="arrow"><i class="fas fa-chevron-down"></i></div>
                            </a>
                            <ul class="accordion">
                                <li><a href="../../admin/AddStudent.php" class="active"><i class="fas fa-users pr-1"></i>Add Students</a></li>
                            </ul>

                        </li>
                        <li>
                            <a href="../../admin/quiz-home.php">
                                <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                                <div class="title">Quiz</div>
                            </a>
                        </li>
                        <li>
                            <a href="../../faq/IndexFaq.php">
                                <div class="icon"><i class="fas fa-info-circle"></i></div>
                                <div class="title">FAQ</div>
                            </a>
                        </li>
                        <li>
                            <a href="../../upload_image.php">
                                <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                                <div class="title">Profile</div>
                            </a>
                        </li>
                        <li>
                            <a href="../../<?php echo $name ?>/<?php echo $name ?>_login.php">
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
                        <a href="../ViewSchoolList.php">School List</a>
                        <a href="../ViewTeacherList.php">Teacher List</a>
                        <a href="../ViewStudentList.php">Student List</a>
                    </div>
                </div>

                <div class="content">
                    <h1>Welcome to tarumt!</h1>
                    <?php if ($_SESSION['role'] == "admin"): ?>
                        <a href="../../manage_post.php?school_name=<?php echo urlencode($school_name); ?>" class="btn btn-primary" style="margin-left: 2.5%; font-size: 20px; padding: 10px 20px;">Add Post</a>
                    <?php endif; ?>

                    <div style="margin-bottom: 20px;"></div>
                    <div class="row row-cols-sm-1 row-cols-md-3 row-cols-xl-4 gx-4 gy-2">
                        <?php
                        $posts = scandir('./post');

                        // Filter out . and .. from the array
                        $posts = array_diff($posts, array('.', '..'));

                        // Sort the posts by their last modified time
                        usort($posts, function ($a, $b) {
                            $fileA = './post/' . $a;
                            $fileB = './post/' . $b;
                            return filemtime($fileB) - filemtime($fileA);
                        });

                        foreach ($posts as $post):
                            // Remove the .html extension for display
                            $display_name = basename($post, '.html');

                            // Get file creation and modification times
                            $file_path = './post/' . $post;
                            $creation_time = date('Y-m-d H:i:s', filectime($file_path));
                            $last_modified_time = filemtime($file_path);

                            // If the last modified time is the same as the creation time, set $last_modified_time_display to empty
                            if ($last_modified_time == filectime($file_path)) {
                                $last_modified_time_display = '';
                            } else {
                                $last_modified_time_display = date('Y-m-d H:i:s', $last_modified_time);
                            }
                        ?>
                            <div class="col-12" id="card-content" style="width: 96%; margin: 10px auto;">
                                <div class="card border-right border-primary rounded-0">
                                    <div class="card-body">
                                        <div class="col-12" >
                                            <!-- Keep the link pointing to the actual file -->
                                            <a href="../../view_post.php?school_name=<?php echo urlencode($school_name); ?>&post=<?php echo urlencode($display_name); ?>.html" title="<?php echo htmlspecialchars($display_name); ?>" class="page-title" style="font-size: 20px;">
                                                <b><?php echo htmlspecialchars($display_name); ?></b>
                                            </a>

                                            <?php
                                            if ($name == 'admin') {
                                                $edit_url = "../../edit_post.php?school_name=" . urlencode(strtolower($school_name)) . "&page=" . urlencode($display_name) . ".html";
                                                $delete_url = "../../delete_post.php?school_name=" . urlencode(strtolower($school_name)) . "&page=" . urlencode($display_name) . ".html";

                                                // // 输出生成的 URL 以便调试
                                                // echo "Edit URL: " . $edit_url . "<br>";
                                                // echo "Delete URL: " . $delete_url . "<br>";

                                                echo "<a href=\"$edit_url\" class=\"btn btn-sm rounded-0\" style=\"background-color: #f0ad4e; color: #000; border: none; font-size: 20px; padding: 10px 20px; border-radius: 12px;\">Edit</a>
<a href=\"$delete_url\" class=\"btn btn-sm rounded-0\" style=\"background-color: #d9534f; color: #fff; border: none; font-size: 20px; padding: 10px 20px; border-radius: 12px;\"onclick=\"return confirm('Are you sure you want to delete this item?');\">Delete</a>";
                                            }
                                            ?>
                                        </div>
                                        <!-- Display creation and last modification times -->
                                        <div class="text-end mt-2" style="font-size: 0.85rem; color: #6c757d;">
                                            <?php if (!empty($last_modified_time_display)) : ?>
                                                <p>Last Modified Time: <?php echo $last_modified_time_display; ?></p>
                                            <?php endif; ?>
                                            <p>Created Time: <?php echo $creation_time; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>
            </div>
        </div>
    </form>
</body>

</html>