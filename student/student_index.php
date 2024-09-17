<?php
session_start();
include('auth.php');
include '../database.php';

if (isset($_SESSION['role']) && $_SESSION['role'] == "teacher") {
    $name = $_SESSION['role'];
} elseif (isset($_SESSION['role']) && $_SESSION['role'] == "admin") {
    $name = $_SESSION['role'];
} elseif (isset($_SESSION['role']) && $_SESSION['role'] == "student") {
    $name = $_SESSION['role'];
} else {
    $name = "Unknown";
}

$userId = $_SESSION['id'];
$query = "SELECT s.username, s.profile_image, sch.school_name 
          FROM student s 
          JOIN school sch ON s.school_id = sch.id 
          WHERE s.id='$userId'";
$result = mysqli_query($con, $query);


$username = "Unknown User";
$schoolName = "Unknown School";
$folderName = "default_school";
$profileImage = '../image/default_profile_image.png';

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $username = $row['username'];
    $schoolName = $row['school_name'];

    if (!empty($schoolName)) {
        $folderName = strtolower(str_replace(' ', '_', $schoolName));
    }

    if (!empty($row['profile_image'])) {
        $profileImage = $row['profile_image'];
    }
}

?>


<!DOCTYPE html>

<html>

<head>
    <title>School Management Project</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./asset/summernote-lite.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./asset/summernote-lite.js"></script>
    <script src="./bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
    <link href="../css/index.css" rel="stylesheet" />

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
        window.onload = function() {
            var msgType = "<?php echo isset($_SESSION['msg']['type']) ? $_SESSION['msg']['type'] : ''; ?>";
            var msgText = "<?php echo isset($_SESSION['msg']['text']) ? $_SESSION['msg']['text'] : ''; ?>";

            if (msgType && msgText) {
                alert(msgText);
                <?php unset($_SESSION['msg']); ?>
            }
        };
    </script>
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
                            <a href="student_index.php">
                                <div class="icon"><i class="fas fa-home"></i></div>
                                <div class="title">Home</div>
                            </a>
                        </li>

                        <li>
                            <a href="../schools/<?php echo htmlspecialchars($folderName); ?>/<?php echo htmlspecialchars($folderName); ?>.php">
                                <div class="icon"><i class="fas fa-hotel"></i></div>
                                <div class="title"><?php echo htmlspecialchars($schoolName); ?></div> <!-- 动态显示学校名称 -->
                            </a>
                        </li>

                        <li>
                            <a href="teacher_list.php">
                                <div class="icon"><i class="fas fa-user-tie"></i></div>
                                <div class="title">Teacher List</div>
                            </a>

                        </li>

                        <li>
                            <a href="quiz_home.php">
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
                            <a href="student_login.php">
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
                    <a href="student_index.php">Home Page</a>
                    | <a href="student_login.php">Logout</a>
                    </div>
                </div>

                <div class="content">
                    <h1></h1>
                    <div style="margin-bottom: 20px;"></div>
                    <div class="row gx-4 gy-2 justify-content-center">
                        <?php
                        $pages = scandir('../pages');

                        // 排除 '.' 和 '..'
                        $pages = array_diff($pages, array('.', '..'));

                        // 获取每个文件的创建时间并进行排序
                        usort($pages, function ($a, $b) {
                            $fileA = '../pages/' . $a;
                            $fileB = '../pages/' . $b;
                            return filemtime($fileB) - filemtime($fileA);
                        });

                        foreach ($pages as $page):
                            // 去掉 .html 扩展名用于显示
                            $display_name = basename($page, '.html');

                            // 获取文件的创建时间和修改时间
                            $file_path = '../pages/' . $page;
                            $creation_time = date('Y-m-d H:i:s', filectime($file_path)); // 使用 filectime 获取创建时间
                            $last_modified_time = filemtime($file_path); // 使用 filemtime 获取最后修改时间

                            // 如果最后修改时间与创建时间相同，则将 $last_modified_time 设为空
                            if ($last_modified_time == filectime($file_path)) {
                                $last_modified_time_display = '';
                            } else {
                                $last_modified_time_display = date('Y-m-d H:i:s', $last_modified_time);
                            }
                        ?>
                            <div class="col-12" style="width: 96%; margin:10px auto;">
                                <div class="card border-right border-primary rounded-0">
                                    <div class="card-body">
                                        <div class="col-12 text-truncate">
                                            <!-- 保持链接指向实际文件 -->
                                            <a href="../view_page.php?page=<?php echo urlencode($display_name) ?>.html" title="<?php echo $display_name ?>" class="page-title" style="font-size: 20px;">
                                                <b><?php echo $display_name ?></b>
                                            </a>

                                            <?php
                                            // 假设 $name 是从数据库中获取的当前用户的角色
                                            if ($name == 'admin') {
                                                echo "<a href=\"../manage_page.php?page=" . urlencode($display_name) . ".html\" class=\"btn btn-sm rounded-0\" style=\"background-color: #f0ad4e; color: #000; border: none; font-size: 20px; padding: 10px 20px; border-radius: 12px;\">Edit</a>
                                <a href=\"../delete_page.php?page=" . urlencode($display_name) . ".html\" class=\"btn btn-sm rounded-0\" style=\"background-color: #d9534f; color: #fff; border: none; font-size: 20px; padding: 10px 20px; border-radius: 12px;\">Delete</a>";
                                            }
                                            ?>
                                        </div>

                                        <!-- 显示创建时间和最后修改时间 -->
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
    </form>

</body>

</html>