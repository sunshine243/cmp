<?php
session_start(); // 确保会话已经启动

// 检查用户是否已登录
if (!isset($_SESSION['username'])) {
    // 如果用户未登录，重定向到登录页面
    header("Location: admin_login.php");
    exit();
}

// 获取用户名
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="../css/index.css">
<title>Admin Dashboard</title>
</head>
<body>
    <div class="profile_info">
        <div class="profile_img">
            <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Image">
        </div>
        <div class="profile_data">
            <p class="name"><?php echo htmlspecialchars($username); ?> Module</p>
            <span><i class="fas fa-map-marker-alt"></i>Kampar, Perak</span>
        </div>
    </div>
</body>
</html>
test admin_index

<?php
session_start(); // 确保会话已经启动
require('database.php'); // 包含数据库连接文件

if (isset($_POST['submit'])) {
    $username = stripslashes($_POST['username']);
    $username = mysqli_real_escape_string($con, $username);
    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($con, $password);
    $password = md5($password); // 对密码进行MD5加密

    $query = "SELECT * FROM `admin` WHERE username='$username' AND password='$password'";
    $result = mysqli_query($con, $query);
    $rows = mysqli_num_rows($result);

    if ($rows == 1) {
        // 设置会话变量
        $_SESSION['username'] = $username;
        header("Location: admin_index.php");
        exit();
    } else {
        echo "<div class='login-box'>
                <h3>Username/password is incorrect.</h3>
                <br/>Click here to <a href='admin_login.php'>Login</a>
              </div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="../css/login.css">
<title>Admin Login</title>
</head>
<body>
<form method="POST" class="login-box">
    <div class="login-header">
        <header>Admin Login</header>
    </div>
    <div class="input-box">
        <input type="text" class="input-field" placeholder="Username" autocomplete="off" name="username" required>
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
