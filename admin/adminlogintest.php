<?php
session_start();
include ('auth.php');
require('database.php');

// 确认数据库连接是否成功
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (isset($_POST['submit'])){
    $username = stripslashes($_POST['username']);
    $username = mysqli_real_escape_string($con, $username);
    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($con, $password);
    $password = md5($password); // Hash the password

    // 输出调试信息
    echo "Username: " . $username . "<br>";
    echo "Hashed Password: " . $password . "<br>";

    $query = "SELECT * FROM `admin` WHERE username='$username' AND password='$password'";
    $result = mysqli_query($con, $query);
    
    // 确认查询是否执行成功
    if (!$result) {
        die("Query failed: " . mysqli_error($con));
    }
    
    $rows = mysqli_num_rows($result);
    
    // 输出调试信息
    echo "Number of rows: " . $rows . "<br>";

    if ($rows == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        echo "<script type='text/javascript'>window.location.href = 'admin_index.php';</script>";
        exit();
    } else {
        echo "<div class='login-box'>
                <h3>Username/password is incorrect.</h3>
                <br/>Click here to <a href='admin_login.php'>Login</a>
              </div>";
    }
}
?>