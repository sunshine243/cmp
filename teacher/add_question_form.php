<?php
session_start();
include('auth.php');
include 'database.php';

$quiz_id = $_GET['quiz_id'];

// 验证 quiz_id 是否存在
if (!isset($quiz_id) || empty($quiz_id)) {
    echo "Quiz ID is missing or invalid.";
    exit;
}

// 获取Quiz的标题
$sql = "SELECT title FROM quizzes WHERE id = $quiz_id";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    $quiz = $result->fetch_assoc();
    $quiz_title = $quiz['title'];
} else {
    echo "Quiz not found.";
    exit;
}

// 获取当前已有的题目数量
$sql = "SELECT COUNT(*) as question_count FROM questions WHERE quiz_id = $quiz_id";
$result = $con->query($sql);
$question_count = 1;  // 默认是1
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $question_count = $data['question_count'] + 1;  // 下一个题目的编号
}

// 获取用户信息
$userId = $_SESSION['id'];
$name = $_SESSION['role']; // 获取用户角色
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
    <title>Add Questions to Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link href="../css/index.css" rel="stylesheet" />
    <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./asset/summernote-lite.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./asset/summernote-lite.js"></script>
    <script src="./bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
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

        form {
            margin-top: 20px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .form-control {
            margin-bottom: 15px;
        }

        .answers-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .answers-group label {
            margin-right: 10px;
        }

        .answers-group input[type="text"] {
            margin-right: 10px;
            flex-grow: 1;
        }

        .answers-group input[type="radio"] {
            margin-right: 10px;
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
                    <li><a href="admin_index.php">
                            <div class="icon"><i class="fas fa-home"></i></div>
                            <div class="title">Home</div>
                        </a></li>
                    <li><a href="#">
                            <div class="icon"><i class="fas fa-hotel"></i></div>
                            <div class="title">School</div>
                        </a></li>
                    <li><a href="#">
                            <div class="icon"><i class="fas fa-user-tie"></i></div>
                            <div class="title">Teachers</div>
                        </a></li>
                    <li><a href="#">
                            <div class="icon"><i class="fas fa-user-graduate"></i></div>
                            <div class="title">Students</div>
                        </a></li>
                    <li><a href="dashboard.php">
                            <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                            <div class="title">Quiz</div>
                        </a></li>
                    <li><a href="../faq/IndexFaq.php">
                            <div class="icon"><i class="fas fa-info-circle"></i></div>
                            <div class="title">FAQ</div>
                        </a></li>
                    <li><a href="upload_image.php">
                            <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                            <div class="title">Profile</div>
                        </a></li>
                    <li><a href="admin_login.php">
                            <div class="icon"><i class="fas fa-sign-out-alt"></i></div>
                            <div class="logout_btn">Logout</div>
                        </a></li>
                </ul>
            </div>
        </div>

        <div class="main_container">
            <div class="navbar">
                <div class="hamburger"><i class="fas fa-bars"></i></div>
                <div class="logo">
                    <a href="teacher_ViewSchool.php">School List</a>
                    <a href="teacher_list.php">Teacher List</a>
                    <a href="student_list.php">Student List</a>
                </div>
            </div>

            <div class="content">
                <h1>Add Question <?php echo $question_count; ?> to "<?php echo htmlspecialchars($quiz_title); ?>"</h1>
                <form action="save_question.php" method="post" class="form-group">
                    <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
                    
                    <label for="question">Question <?php echo $question_count; ?>:</label>
                    <textarea id="question" name="question_text" class="form-control" required></textarea>

                    <label for="score">Score for this question:</label>
                    <input type="number" id="score" name="score" class="form-control" value="1" min="1" required>

                    <div class="answers-group">
                        <label for="answer1">Answer 1:</label>
                        <input type="text" id="answer1" name="answers[]" class="form-control" required>
                        <input type="radio" name="correct_answer" value="0" required> Correct
                    </div>

                    <div class="answers-group">
                        <label for="answer2">Answer 2:</label>
                        <input type="text" id="answer2" name="answers[]" class="form-control" required>
                        <input type="radio" name="correct_answer" value="1" required> Correct
                    </div>

                    <div class="answers-group">
                        <label for="answer3">Answer 3:</label>
                        <input type="text" id="answer3" name="answers[]" class="form-control" required>
                        <input type="radio" name="correct_answer" value="2" required> Correct
                    </div>

                    <div class="answers-group">
                        <label for="answer4">Answer 4:</label>
                        <input type="text" id="answer4" name="answers[]" class="form-control" required>
                        <input type="radio" name="correct_answer" value="3" required> Correct
                    </div>

                    <div>
                        <input type="submit" name="add_another" value="Add Another Question" class="btn btn-primary">
                        <input type="submit" name="finish" value="Finish and Return to Dashboard" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $(".arrow").click(function() {
                $(this).find("i").toggleClass("fa-chevron-down fa-chevron-up");
                $(this).siblings(".accordion").slideToggle();
            });

            $(".siderbar_menu li").click(function() {
                var isActive = $(this).hasClass("active");
                $(".siderbar_menu li").removeClass("active");
                $(".accordion").slideUp();
                $(".arrow i").removeClass("fa-chevron-up").addClass("fa-chevron-down");

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
</body>

</html>
