<?php
session_start();


include('auth.php');
require('../database.php');
if (isset($_POST['submit'])) {
    $username = stripslashes($_POST['username']);
    $username = mysqli_real_escape_string($con, $username);
    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($con, $password);
    $password = md5($password); // Hash the password

    $query = "SELECT * FROM `student` WHERE username='$username' AND password='$password'";

    $result = mysqli_query($con, $query) or die(mysqli_error($con));
    $rows = mysqli_num_rows($result);

    if ($rows == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = 'student';

        echo "登录成功! 当前角色是: " . $_SESSION['role'] . "<br>";
        echo "当前用户ID是: " . $_SESSION['id'] . "<br>";
        echo "<script type='text/javascript'>window.location.href = 'student_index.php';</script>";

        exit();
    } else {
        echo "<div class='login-box'>
                <h3>Username/password is incorrect.</h3>
                <br/>Click here to <a href='student_login.php'>Login</a>
              </div>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/login.css">
    <title>Student Login</title>
</head>

<body>
    <form method="POST" class="login-box">
        <div class="login-header">
            <header>Student Login</header>
        </div>
        <div class="input-box">
            <input type="text" class="input-field" placeholder="Email" autocomplete="off" name="username" required>
        </div>
        <div class="input-box">
            <input type="password" class="input-field" placeholder="Password" autocomplete="off" name="password" required>
        </div>
        <div class="forgot">
            <section>
                <input type="checkbox" id="check">
                <label for="check">Remember me</label>
            </section>
            <section>
                <a href="#">Forgot password</a>
            </section>
        </div>
        <div class="input-submit">
            <input type="submit" class="submit-btn" name="submit" value="Sign In">
        </div>
        <div class="sign-up-link">
            <p>As a teacher? <a href="../teacher/teacher_login.php">Click here</a></p>
        </div>
    </form>
</body>

</html>