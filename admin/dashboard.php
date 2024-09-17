<?php
session_start();
include('auth.php');
include 'database.php';

// 检查用户角色，确保用户已经登录并且具有正确的权限
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
    <title>School Management Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
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

        .button {
            margin-bottom: 20px;
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
                    <li><a href="admin_index.php"><div class="icon"><i class="fas fa-home"></i></div><div class="title">Home</div></a></li>
                    <li><a href="#"><div class="icon"><i class="fas fa-hotel"></i></div><div class="title">School</div><div class="arrow"><i class="fas fa-chevron-down"></i></div></a></li>
                    <li><a href="#"><div class="icon"><i class="fas fa-user-tie"></i></div><div class="title">Teachers</div><div class="arrow"><i class="fas fa-chevron-down"></i></div></a></li>
                    <li><a href="#"><div class="icon"><i class="fas fa-user-graduate"></i></div><div class="title">Students</div><div class="arrow"><i class="fas fa-chevron-down"></i></div></a></li>
                    <li><a href="dashboard.php"><div class="icon"><i class="fas fa-clipboard-list"></i></div><div class="title">Quiz</div></a></li>
                    <li><a href="../faq/IndexFaq.php"><div class="icon"><i class="fas fa-info-circle"></i></div><div class="title">FAQ</div></a></li>
                    <li><a href="upload_image.php"><div class="icon"><i class="fas fa-calendar-alt"></i></div><div class="title">Profile</div></a></li>
                    <li><a href="admin_login.php"><div class="icon"><i class="fas fa-sign-out-alt"></i></div><div class="logout_btn">Logout</div></a></li>
                </ul>
            </div>
        </div>

        <div class="main_container">
            <div class="navbar">
                <div class="hamburger"><i class="fas fa-bars"></i></div>
                <div class="logo">
                    <a href="ViewSchoolList.php">School List</a>
                    <a href="ViewTeacherList.php">Teacher List</a>
                    <a href="ViewStudentList.php">Student List</a>
                </div>
            </div>

            <div class="content">
                <h1>Quiz Dashboard</h1>
                <div class="button">
                    <a href="create_quiz.php" class="btn btn-primary">Create Quiz</a>
                    <a href="add_question.php" class="btn btn-primary">Add Questions</a>
                    </div>
                <table>
                    <tr>
                        <th>Title</th>
                        <th>Number of Questions</th>
                        <th>Time Limit</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    // 获取所有的Quiz
                    $sql = "SELECT q.id, q.title, q.time_limit, COUNT(que.id) as question_count 
                            FROM quizzes q
                            LEFT JOIN questions que ON q.id = que.quiz_id
                            GROUP BY q.id";
                    $result = $con->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['question_count']) . "</td>";
                            echo "<td>" . ($row['time_limit'] > 0 ? htmlspecialchars($row['time_limit']) . ' minutes' : 'NA') . "</td>";
                            echo "<td>
                                    <a href='edit_quiz.php?id=" . htmlspecialchars($row['id']) . "'>Edit</a> |
                                    <a href='delete_quiz.php?id=" . htmlspecialchars($row['id']) . "' onclick='return confirm(\"Are you sure you want to delete this quiz?\");'>Delete</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No quizzes available</td></tr>";
                    }

                    $con->close();
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
