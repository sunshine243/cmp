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


// Clear session messages
if (isset($_SESSION['msg'])) unset($_SESSION['msg']);
if (isset($_SESSION['POST'])) unset($_SESSION['POST']);

// Retrieve the school_name and post from the URL and sanitize
if (isset($_GET['school_name']) && !empty($_GET['school_name'])) {
    $school_name = htmlspecialchars($_GET['school_name'], ENT_QUOTES, 'UTF-8');
} else {
    $_SESSION['msg']['type'] = 'danger';
    $_SESSION['msg']['text'] = 'School name is not specified.';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

if (isset($_GET['page']) && !empty($_GET['page'])) {
    $page = basename($_GET['page']); // Prevent directory traversal
} else {
    $_SESSION['msg']['type'] = 'danger';
    $_SESSION['msg']['text'] = 'Page is not specified.';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

// Define the file path
$file_path = "./schools/" . $school_name . "/post/" . $page;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve POST data and sanitize
    $filename = isset($_POST['filename']) ? basename($_POST['filename']) : ''; // Prevent path traversal
    $fname = isset($_POST['fname']) ? htmlspecialchars($_POST['fname'], ENT_QUOTES, 'UTF-8') : '';
    $content = isset($_POST['content']) ? htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8') : '';

    // Validate file name
    if (empty($fname) || !preg_match('/^[a-zA-Z0-9_-]+$/', $fname)) {
        $_SESSION['msg']['type'] = 'danger';
        $_SESSION['msg']['text'] = 'Invalid file name.';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

    // Create the new file path
    $new_file_path = "./schools/" . $school_name . "/post/" . $fname . ".html";

    // Save the content to the file
    if (file_put_contents($new_file_path, $content) !== false) {
        // Delete old file if it exists and is different from the new file
        if ($file_path !== $new_file_path && is_file($file_path)) {
            unlink($file_path);
        }

        //$_SESSION['msg']['type'] = 'success';
        //$_SESSION['msg']['text'] = 'Post content successfully saved.';
    } else {
        //$_SESSION['msg']['type'] = 'danger';
        //$_SESSION['msg']['text'] = 'Failed to save post content. Please check file permissions.';
    }

    // Redirect to the back to the list page
    header('Location: ./schools/' . $school_name . '/' . strtolower($school_name) . '.php');
    exit();
} else {
    // Display the form with existing content
    if (is_file($file_path)) {
        $content = htmlspecialchars(file_get_contents($file_path), ENT_QUOTES, 'UTF-8');
    } else {
        $content = '';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Main Post</title>
    <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./asset/summernote-lite.css">
    <script src="./asset/summernote-lite.js"></script>
    <script src="./bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="./css/index.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
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

        input.form-control.border-0 {
            transition: border .3s linear
        }

        input.form-control.border-0:focus {
            box-shadow: unset !important;
            border-color: var(--bs-info) !important
        }

        .note-editor.note-frame .note-editing-area .note-editable,
        .note-editor.note-airframe .note-editing-area .note-editable {
            background: var(--bs-white);
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
                    <li>
                        <a href="admin_login.php">
                            <div class="icon"><i class="fas fa-sign-out-alt"></i></div>
                            <div class="logout_btn">Logout</div>
                        </a>
                    </li>
                </ul>
                <!-- <div class="logout_btn">
                        <?php
                        if (isset($_SESSION['username'])) {
                            echo '<a href="admin_login.php">Logout</a>';
                        } else {
                            // 如果没有设置用户名会话，则显示登录按钮或其他登录相关的内容
                            // 这里可以根据需要添加适当的登录按钮或链接
                            echo '<a href="admin_login.php">Logout</a>';
                        }
                        ?>
                    </div> -->

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
        <div class="card">
            <div class="card-header">
                Edit Post Content
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <input type="hidden" name="filename" value="<?php echo htmlspecialchars($page, ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="form-group col-6">
                        <label for="fname" class="control-label">File Name <span class="text-info"><small>([a-z0-9A-Z_-])</small></span></label>
                        <input type="text" pattern="[a-z0-9A-Z_-]+" name="fname" id="fname" autofocus autocomplete="off" 
                        class="form-control form-control-sm border-0 border-bottom rounded-0" 
                        value="<?php echo htmlspecialchars(basename($page, '.html'), ENT_QUOTES, 'UTF-8'); ?>" required>
                        <span class="text-info"><small>This will be added with .html file extension upon saving.</small></span>
                    </div>
                    <div class="form-group col-12">
                        <label for="content" class="control-label">Content</label>
                        <textarea name="content" id="summernote" class="summernote" required><?php echo $content; ?></textarea>
                    </div>
                    <button class="btn btn-sm rounded-0 btn-primary" type="submit">Save</button>
                    <a class="btn btn-sm rounded-0 btn-light" href="./schools/<?php echo strtolower($school_name); ?>/<?php echo strtolower($school_name); ?>.php">Cancel</a>
                </form>
            </div>
        </div>
    </div>

</body>
<script>
    $('#summernote').summernote({
        placeholder: 'Hello stand alone ui',
        tabsize: 2,
        height: 120,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
</script>

</html>