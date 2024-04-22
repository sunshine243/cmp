<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="login.css">
<title>Admin Login</title>
</head>
<body>
<?php
require('database.php');
if (isset($_POST['submit'])){
$username = stripslashes($_POST['username']);
$username = mysqli_real_escape_string($con,$username);
$password = stripslashes($_POST['password']);
$password = mysqli_real_escape_string($con,$password);
 $query = "SELECT *
 FROM `admin`
 WHERE username='$username'
 AND password='".md5($password)."'"
 ;
$result = mysqli_query($con,$query) or die(mysqli_error($con));
$rows = mysqli_num_rows($result);
 if($rows==1){
 $_SESSION['username'] = $username;
 echo "<script> alert(" + $username + "）</script>";
 header("Location: index.php");
 exit();
 }else{
echo "<div class='login-box'>
<h3>Username/password is incorrect.</h3>
<br/>Click here to <a href='login.php'>Login</a></div>";
}
 }else{
?>
<form method = "POST" class="login-box">
        <div class="login-header">
            <header>Login</header>
        </div>
        <div class="input-box">
            <input type="text" class="input-field" placeholder="Email" autocomplete="off" required>
        </div>
        <div class="input-box">
            <input type="password" class="input-field" placeholder="Password" autocomplete="off" required>
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
            <a href="index.php" class="submit-btn" name = "submit">Sign In</a>
        </div>
        <div class="sign-up-link">
            <p>Don't have account? <a href="registration.php">Sign Up</a></p>
        </div>
 </form>
    <?php } ?>
</body>
</html>

