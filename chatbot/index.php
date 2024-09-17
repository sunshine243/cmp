<?php
session_start();
include('../auth.php');
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
	<script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
	<link href="../css/index.css" rel="stylesheet" />
	<link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="./asset/summernote-lite.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="./asset/summernote-lite.js"></script>
	<script src="./bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
    <title>Chatbot Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e7f1ff;
            margin: 0;
            padding: 0;
        }

        .container1 {
            width: 96%;
            margin: 10px auto;
        }

        #chatbox {
            width: 500px;
            height: 600px;
            border: 1px solid #ccc;
            overflow-y: auto;
            padding: 20px;
            background-color: #fff;
            margin: 20px 0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        #input-container {
            display: flex;
            align-items: center;
            width: 500px;
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 400px;
            padding: 10px;
            margin-right: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
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
                            <a href="quiz.php">
                                <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                                <div class="title">Quiz</div>
                            </a>
                        </li>
                        <li>
                            <a href="../testing.php">
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
                        <a href="ViewSchoolList.php">School List</a>
                        <a href="ViewTeacherList.php">Teacher List</a>
                        <a href="ViewStudentList.php">Student List</a>
                    </div>
                </div>

                <div class="content">
                    <div id="chatbox">
                        <div id="loading" class="loading-message" style="display: none;">Bot is typing...</div>
                    </div>

                    <div id="input-container">
                        <input type="text" id="text" onkeypress="checkEnter(event);">
                        <button onclick="generateResponse();">Send</button>
                    </div>

                    <script src="script.js"></script>
                </div>
            </div>
        </div>
</body>

</html>