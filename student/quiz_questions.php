<?php
session_start();
include('../database.php');

$quiz_id = $_GET['quiz_id'];

// Validate quiz_id
if (!isset($quiz_id) || empty($quiz_id)) {
    echo "Quiz ID is missing or invalid.";
    exit;
}

// Get quiz time limit and title
$sql = "SELECT time_limit, title FROM quizzes WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $quiz = $result->fetch_assoc();
    $time_limit = $quiz['time_limit'];
    $quiz_title = $quiz['title'];
} else {
    echo "Quiz not found.";
    exit;
}

// Retrieve user information
$userId = $_SESSION['id'];
$query = "SELECT s.username, s.profile_image, sch.school_name 
          FROM student s 
          JOIN school sch ON s.school_id = sch.id 
          WHERE s.id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$user_result = $stmt->get_result();

$username = "Unknown User";
$schoolName = "Unknown School";
$folderName = "default_school";
$profileImage = '../image/default_profile_image.png';

if ($user_result && $user_result->num_rows > 0) {
    $row = $user_result->fetch_assoc();
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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($quiz_title); ?> - Quiz Questions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./asset/summernote-lite.css">
    <link href="../css/index.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./asset/summernote-lite.js"></script>
    <script src="./bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
    <style>
        :root {
            --bs-success-rgb: 71, 222, 152 !important;
        }

        html, body {
            height: 100%;
            width: 100%;
            font-family: Apple Chancery, cursive;
        }

        .btn-info.text-light:hover,
        .btn-info.text-light:focus {
            background: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        /* Flexbox for the title and timer */
        .quiz-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .quiz-title {
            font-size: 2rem;
        }

        .quiz-timer {
            font-size: 1.2rem;
            font-weight: bold;
            color: #ff0000;
        }

        /* Make the time wrapper fixed at the top of the screen */
        .time-wrapper {
            position: fixed;
            top: 0;
            right: 20px;
            background-color: white;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1.2rem;
            z-index: 1000; /* Make sure it stays on top */
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .time-wrapper span {
            margin-left: 10px;
            font-weight: bold;
            color: #ff0000;
        }

        /* Styling for the radio buttons and answers in vertical form */
        .answer-wrapper {
            display: block;
            margin-bottom: 10px;
            font-size: 1.1rem; /* Bigger font for the answers */
        }

        .answer-wrapper input[type="radio"] {
            margin-right: 10px; /* Space between the button and answer text */
        }

        .question-wrapper {
            margin-bottom: 20px;
        }

        /* Add margin to avoid overlap with the fixed timer */
        .content {
            margin-top: 60px; /* Adjust this to the height of the fixed element */
        }
    </style>
    <script>
    var time_limit = <?php echo $time_limit * 60; ?>; // Convert to seconds
    if (time_limit > 0) {
        var timer = setInterval(function() {
            if (time_limit <= 0) {
                clearInterval(timer);
                alert('Time is up! Submitting your quiz.');
                document.getElementById('quiz_form').submit();
            } else {
                time_limit--;
                var minutes = Math.floor(time_limit / 60);
                var seconds = time_limit % 60;
                document.getElementById('timer').innerHTML = minutes + "m " + (seconds < 10 ? "0" : "") + seconds + "s ";
            }
        }, 1000);
    }
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
                        <li><a href="student_index.php"><div class="icon"><i class="fas fa-home"></i></div><div class="title">Home</div></a></li>
                        <li><a href="../schools/<?php echo htmlspecialchars($folderName); ?>/<?php echo htmlspecialchars($folderName); ?>.php"><div class="icon"><i class="fas fa-hotel"></i></div><div class="title"><?php echo htmlspecialchars($schoolName); ?></div></a></li>
                        <li><a href="teacher_list.php"><div class="icon"><i class="fas fa-user-tie"></i></div><div class="title">Teacher List</div></a></li>
                        <li><a href="quiz_home.php"><div class="icon"><i class="fas fa-clipboard-list"></i></div><div class="title">Quiz</div></a></li>
                        <li><a href="../faq/IndexFaq.php"><div class="icon"><i class="fas fa-info-circle"></i></div><div class="title">FAQ</div></a></li>
                        <li><a href="upload_image.php"><div class="icon"><i class="fas fa-calendar-alt"></i></div><div class="title">Profile</div></a></li>
                        <li><a href="student_login.php"><div class="icon"><i class="fas fa-sign-out-alt"></i></div><div class="logout_btn">Logout</div></a></li>
                    </ul>
                </div>
            </div>

            <div class="main_container">
                <div class="navbar">
                    <div class="hamburger">
                        <i class="fas fa-bars"></i>
                    </div>
                    <div class="logo">
                        <a href="student_index.php">Home Page</a> | <a href="student_login.php">Logout</a>
                    </div>
                </div>

                <div class="content">
                    <div class="quiz-header">
                        <h1 class="quiz-title"><?php echo htmlspecialchars($quiz_title); ?></h1>
                        <?php if ($time_limit > 0): ?>
                            <div class="time-wrapper">Time Remaining: <span id="timer"></span></div>
                        <?php endif; ?>
                    </div>

                    <form id="quiz_form" action="submit_answers.php" method="post" onsubmit="return confirm('Are you sure you want to submit your answers?');">
                        <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">

                        <?php
                        // Get quiz questions
                        $sql = "SELECT id, question_text FROM questions WHERE quiz_id = ?";
                        $stmt = $con->prepare($sql);
                        $stmt->bind_param("i", $quiz_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        $question_number = 1;  // Initialize question number
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $question_id = $row['id'];
                                echo "<div class='question-wrapper'><h3>Question " . $question_number . ": " . htmlspecialchars($row['question_text']) . "</h3>";

                                // Get the answers for the current question
                                $sql_answers = "SELECT id, answer_text FROM answers WHERE question_id = ?";
                                $stmt = $con->prepare($sql_answers);
                                $stmt->bind_param("i", $question_id);
                                $stmt->execute();
                                $result_answers = $stmt->get_result();

                                while($answer = $result_answers->fetch_assoc()) {
                                    echo "<div class='answer-wrapper'><input type='radio' name='answer_$question_id' value='" . $answer['id'] . "' required> " . htmlspecialchars($answer['answer_text']) . "</div>";
                                }
                                echo "</div><br>";
                                $question_number++;  // Increment question number
                            }
                        } else {
                            echo "No questions available for this quiz.";
                        }

                        // Close statement and connection
                        $stmt->close();
                        $con->close();
                        ?>

                        <input type="submit" value="Submit Answers">
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