<?php
session_start();
include('../database.php');

// Ensure the user is logged in before accessing the page
if (!isset($_SESSION['id'])) {
    echo "User not logged in. Please log in first.";
    exit;
}

$userId = $_SESSION['id'];  // User ID from session

// Retrieve username based on user ID
$query = "SELECT s.username, s.profile_image, sch.school_name 
          FROM student s 
          JOIN school sch ON s.school_id = sch.id 
          WHERE s.id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $userId);  // Bind user ID to the query
$stmt->execute();
$result = $stmt->get_result();

$username = "Unknown User";
$schoolName = "Unknown School";
$folderName = "default_school";
$profileImage = '../image/default_profile_image.png';

if ($result && mysqli_num_rows($result) > 0) {
    $row = $result->fetch_assoc();
    $username = $row['username'];
    $schoolName = $row['school_name'];

    if (!empty($schoolName)) {
        $folderName = strtolower(str_replace(' ', '_', $schoolName));
    }

    if (!empty($row['profile_image'])) {
        $profileImage = $row['profile_image'];
    }
}

// Fetch available quizzes and user scores
$sql = "SELECT q.id, q.title, q.time_limit, 
        (SELECT score FROM user_scores WHERE quiz_id = q.id AND username = ? ORDER BY created_at DESC LIMIT 1) as user_score,
        (SELECT total_score FROM user_scores WHERE quiz_id = q.id AND username = ? ORDER BY created_at DESC LIMIT 1) as total_score
        FROM quizzes q";
$stmt = $con->prepare($sql);
$stmt->bind_param("ss", $username, $username);  // Bind username twice for the subqueries
$stmt->execute();
$quiz_result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Quizzes</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }
    </style>
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
                        <li><a href="student_index.php">
                                <div class="icon"><i class="fas fa-home"></i></div>
                                <div class="title">Home</div>
                            </a></li>
                        <li><a href="../schools/<?php echo htmlspecialchars($folderName); ?>/<?php echo htmlspecialchars($folderName); ?>.php">
                                <div class="icon"><i class="fas fa-hotel"></i></div>
                                <div class="title"><?php echo htmlspecialchars($schoolName); ?></div>
                            </a></li>
                        <li><a href="teacher_list.php">
                                <div class="icon"><i class="fas fa-user-tie"></i></div>
                                <div class="title">Teacher List</div>
                            </a></li>
                        <li><a href="quiz_home.php">
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
                        <li><a href="student_login.php">
                                <div class="icon"><i class="fas fa-sign-out-alt"></i></div>
                                <div class="logout_btn">Logout</div>
                            </a></li>
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
                    <h1>Available Quizzes</h1>
                    <div style="margin-bottom: 20px;"></div>
                    <table>
                        <tr>
                            <th>Quiz Title</th>
                            <th>Time Limit</th>
                            <th>Your Score</th>
                            <th>Actions</th>
                        </tr>
                        <?php
                        if ($quiz_result->num_rows > 0) {
                            while ($row = $quiz_result->fetch_assoc()) {
                                // Now proceed with your existing logic for score display
                                if ($row['user_score'] !== null) {
                                    $score_display = $row['user_score'] . " / " . $row['total_score'];
                                } else {
                                    $score_display = "Not Attempted";
                                }

                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                echo "<td>" . ($row['time_limit'] > 0 ? $row['time_limit'] . ' minutes' : 'No Time Limit') . "</td>";
                                echo "<td>" . $score_display . "</td>";
                                echo "<td><a href='quiz_questions.php?quiz_id=" . $row['id'] . "'>Start Quiz</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No quizzes available</td></tr>";
                        }
                        ?>


                    </table>
                </div>
            </div>
        </div>

    </form>

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

</html