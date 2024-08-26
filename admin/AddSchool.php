<?php
session_start();
include('auth.php');
include 'database.php';

if (!isset($_SESSION['id'])) {
    header("Location: admin_login.php");
    exit();
}
$status = "";

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

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $school_name = htmlspecialchars($_POST['school_name']);
    $folder_name = strtolower(str_replace(' ', '_', $school_name));
    $pageName = $folder_name . '.php';

    // Insert school into the database
    $query = "INSERT INTO school (school_name) VALUES ('$school_name')";
    $result = mysqli_query($con, $query);

    if ($result) {
        // Generate the folder name and paths
        $main_folder_path = "../schools/$folder_name";  // Main school folder path
        $posts_folder_path = "$main_folder_path/post";  // Posts subfolder path

        // Create the main folder and posts folder if they don't already exist
        if (!file_exists($main_folder_path)) {
            mkdir($main_folder_path, 0777, true); // Create directory with full permissions
        }
        if (!file_exists($posts_folder_path)) {
            mkdir($posts_folder_path, 0777, true); // Create directory with full permissions
        }

        // Generate content for the new page using the default template
        $content = <<<HTML
    <?php
    session_start();
    include ('../../admin/auth.php');
    include '../../admin/database.php';

    if (isset(\$_SESSION['role']) && \$_SESSION['role'] == "teacher") {
        \$name = \$_SESSION['role'];
    } elseif (isset(\$_SESSION['role']) && \$_SESSION['role'] == "admin") {
        \$name = \$_SESSION['role'];
    }

    if (!isset(\$_SESSION['id'])) {
        header("Location: ../". \$name ."_login.php");
        exit();
    }
    
    
    \$userId = \$_SESSION['id'];
    \$query = "SELECT username, profile_image FROM " . \$name . " WHERE id='\$userId'";
    \$result = mysqli_query(\$con, \$query);

    if (\$result && mysqli_num_rows(\$result) > 0) {
    \$row = mysqli_fetch_assoc(\$result);
    \$username = \$row['username']; // 从数据库中获取用户名
    if (isset(\$row['profile_image']) && !empty(\$row['profile_image'])) {
        \$profileImage = '../' . \$row['profile_image'];
    } else {
        \$profileImage = '../../image/default_profile_image.png';
    }
    } else {
    // 如果查询失败或者没有找到用户，则使用默认图片并处理错误
    \$username = "Unknown User";
    \$profileImage = '../../image/default_profile_image.png';  //default image
    }
    ?>
    
    <!DOCTYPE html>
    <html>
    <head>
        <title>$school_name</title>
        <meta charset="UTF-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            .btn-info.text-light:hover,.btn-info.text-light:focus{
                background: #000;
            }
        </style>
    
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
    
        <script>
            \$(document).ready(function () {
                \$(".arrow").click(function () {
                    \$(this).find("i").toggleClass("fa-chevron-down fa-chevron-up");
                    \$(this).siblings(".accordion").slideToggle();
                });
    
                \$(".siderbar_menu li").click(function () {
                    // Check if the current item is active
                    var isActive = \$(this).hasClass("active");
    
                    // Remove 'active' class from all items
                    \$(".siderbar_menu li").removeClass("active");
                    // Close all accordions
                    \$(".accordion").slideUp();
                    // Change all arrow icons to 'fa-chevron-down'
                    \$(".arrow i").removeClass("fa-chevron-up").addClass("fa-chevron-down");
    
                    // If the clicked item was not active, make it active and open its accordion
                    if (!isActive) {
                        \$(this).addClass("active");
                        \$(this).find(".accordion").slideDown();
                        \$(this).find(".arrow i").toggleClass("fa-chevron-down fa-chevron-up");
                    }
                });
    
                \$(".hamburger").click(function () {
                    \$(".wrapper").addClass("active");
                });
    
                \$(".close, .bg_shadow").click(function () {
                    \$(".wrapper").removeClass("active");
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
                                <img src="<?php echo htmlspecialchars(\$profileImage); ?>" alt="Profile Image">
                            </div>
                            <div class="profile_data">
                                <p class="name"><?php echo htmlspecialchars(\$username); ?></p>
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
                            if (isset(\$_SESSION['username'])) {
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
                        <h1>Welcome to $school_name!</h1>
                        <p>This is the auto-generated page for $school_name.</p>
                        <?php if (\$_SESSION['role'] == "admin"): ?>
                        <a href="../../../manage_post.php" class="btn btn-primary">Add Post</a>
                    <?php endif; ?>
                    
                        <div style="margin-bottom: 20px;"></div>
                    <div class="row row-cols-sm-1 row-cols-md-3 row-cols-xl-4 gx-4 gy-2">
                    <?php 
                        \$posts = scandir('./post');
                        asort(\$posts); 
                        foreach(\$posts as \$post):
                        if(in_array(\$post,array('.','..')))
                        continue;
                    ?>
                    <div class="col" style="margin: 10px 0; width: auto;">
                        <div class="card border-right border-primary rounded-0">
                            <div class="card-body">
                                <div class="col-12 text-truncate">
                                    <a href="../view_page.php?page=<?php echo urlencode(\$post) ?>" title="<?php echo \$page ?>" class=""><b><?php echo \$post ?></b></a></div>
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
    HTML;

        // Save the new page
        $file_path = "../schools/$school_name/" . $pageName;
        if (file_put_contents($file_path, $content)) {
            // Save page information to a list file
            $listFile = 'list.txt';
            file_put_contents($listFile, $school_name . '|' . $file_path . "\n", FILE_APPEND);

            $status = "School '$school_name' has been successfully added, and the page has been created.";
        } else {
            $status = "School '$school_name' was added successfully, but the page creation failed.";
        }
    } else {
        $status = "Failed to add school.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
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
    <title>Add School</title>
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
                    <a href="dashboard.php">User Dashboard</a>
                    <a href="ViewSchoolList.php">View School List</a>
                </div>
            </div>

            <div class="content">
                <h1>Add New School</h1>
                <form method="post" action="">
                    <label for="school_name">Enter School Name:</label>
                    <input type="text" id="school_name" name="school_name" required>
                    <button type="submit">Add</button>
                </form>

                <?php if (!empty($status)) { ?>
                    <p style="color:#008000;"><?php echo $status; ?></p>
                <?php } ?>
            </div>

        </div>
    </div>
</body>

</html>