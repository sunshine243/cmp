<?php
session_start();
include('../auth.php');
include '../database.php';

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
            $profileImage = str_replace('./', './', $row['profile_image']);
        } else if ($name == 'teacher') {
            $profileImage = str_replace('./', './', $row['profile_image']);
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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add FAQ</title>
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../asset/summernote-lite.css">
    <script src="../asset/summernote-lite.js"></script>
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="../css/index.css" rel="stylesheet">
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

        .btn-info.text-light:hover,
        .btn-info.text-light:focus {
            background: #000;
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
						<li>
							<a href="../<?php echo $name; ?>/<?php echo $name; ?>_index.php">
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
								<?php
								if ($name == "admin") {
									echo '<li><a href="../admin/AddSchool.php" class="active"><i class="fas fa-user-plus pr-1"></i>Add School</a></li>';
								} elseif ($name == "teacher") {
									echo '<li><a href="../teacher/teacher_ViewSchool.php" class="active"><i class="fas fa-user-plus pr-1"></i>School List</a></li>';
								}
								?>

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
							<a href="../<?php echo ($name === 'student') ? $name . '/quiz_home.php' : $name . '/dashboard.php'; ?>">
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
							<a href="../<?php echo htmlspecialchars($name); ?>/upload_image.php">
								<div class="icon"><i class="fas fa-calendar-alt"></i></div>
								<div class="title">Profile</div>
							</a>
						</li>
						<li>
							<a href="../<?php echo $name; ?>/<?php echo $name; ?>_login.php">
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
						<a href="../<?php echo $name; ?>/<?php echo $name; ?>_index.php">Home</a>
						<a href="../<?php echo $name; ?>/<?php echo $name; ?>_login.php">Logout</a>					
					</div>
            </div>
            <div class="container" style="margin-top: 50px; margin-bottom: 50px;">
                <div class="row">
                    <div class="col-md-12">
                        <h1>Add FAQ</h1>
                        <form method="POST">
                            <div class="form-group">
                                <label>Enter Question</label>
                                <input type="text" name="question" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Enter Answer</label>
                                <textarea name="content" id="summernote" class="summernote" required></textarea>
                            </div>

                            <input type="submit" name="submit" class="btn btn-info" value="Add FAQ">
                            <a href="./IndexFaq.php" class="btn btn-danger">Cancel</a>
                        </form>
                    </div>
                </div>

                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
                    $question = mysqli_real_escape_string($con, $_POST['question']);
                    $answer = mysqli_real_escape_string($con, $_POST['content']);

                    $sql = "INSERT INTO faqs (question, answer) VALUES ('$question', '$answer')";

                    if (mysqli_query($con, $sql)) {
                        echo "<p class='text-success'>FAQ has been successfully added!</p>";
                    } else {
                        echo "<p class='text-danger'>Error: " . mysqli_error($con) . "</p>";
                    }
                }

                // Fetch and display all FAQs at the bottom
                $sql = "SELECT * FROM faqs";
                $result = $con->query($sql);
                $qid =1;

                if ($result->num_rows > 0) {
                    echo '<div class="row"><div class="col-md-12"><h2 class="text-center">Existing FAQs</h2><table class="table table-bordered">';
                    echo '<thead><tr><th>ID</th><th>Question</th><th>Answer</th><th>Actions</th></tr></thead>';
                    echo '<tbody>';
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $qid . '</td>';
                        echo '<td>' . htmlspecialchars($row['question']) . '</td>';
                        echo '<td>' . $row['answer'] . '</td>';
                        echo '<td><a href="EditFaq.php?id=' . $row['id'] . '" class="btn btn-warning btn-sm">Edit</a>';
                        echo '<form method="POST" action="DeleteFaq.php" onsubmit="return confirm(\'Are you sure you want to delete this FAQ?\');" style="display:inline;">';
                        echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
                        echo '<input type="submit" value="Delete" class="btn btn-danger btn-sm"></form></td>';
                        echo '</tr>';
                        $qid++;
                    }
                    echo '</tbody></table></div></div>';
                } else {
                    echo '<p class="text-center">No FAQs found.</p>';
                }
                ?>
            </div>
        </div>
    </div>

</body>
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

        $('#summernote').summernote({
            height: 300,
            callbacks: {
                onImageUpload: function(files) {
                    var data = new FormData();
                    data.append('file', files[0]);

                    $.ajax({
                        url: 'upload_image_faq.php',
                        method: 'POST',
                        data: data,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            var result = JSON.parse(response);
                            if (result.url) {
                                $('#summernote').summernote('insertImage', result.url);
                            } else {
                                console.error('Upload failed:', result.error);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Upload error:', status, error);
                        }
                    });
                }
            }
        });
    });
</script>

</html>