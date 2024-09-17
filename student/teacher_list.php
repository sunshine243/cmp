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
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link href="../css/index.css" rel="stylesheet" />

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"> </script>
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
                            <a href="dashboard.php">
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

                <div class="ViewTeacherList">
                    <h2>Teacher List</h2>
                    <table width="100%" border="1" style="border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th><strong>No.</strong></th>
                                <th><strong>Full Name</strong></th>
                                <th><strong>UserName</strong></th>
                                <th><strong>Email</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 1;
                            $sel_query = "SELECT * FROM teacher ORDER BY id DESC;";
                            $result = mysqli_query($con, $sel_query);
                            if (mysqli_num_rows($result) == 0) {
                                echo '<tr><td colspan="5" class="empty-list">The list is empty</td></tr>';
                            } else {
                                while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                    <tr>
                                        <td align="center"><?php echo $count; ?></td>
                                        <td align="center"><?php echo $row["full_name"]; ?></td>
                                        <td align="center"><?php echo $row["username"]; ?></td>
                                        <td align="center"><?php echo $row["email"]; ?></td>
                                    </tr>
                            <?php
                                    $count++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
</body>

</html>