<?php
//include("auth.php");
require('database.php');

$id = $_REQUEST['id'];
$query = "SELECT * FROM teacher where id='" . $id . "'";
$result = mysqli_query($con, $query) or die(mysqli_error($con));
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Update Teacher List</title>
</head>

<body>
    <p><a href="dashboard.php">User Dashboard</a>
        | <a href="AddTeacher.php">Add New Teacher</a></p>
    <h1>Update Teacher List</h1>
    <?php
    $status = "";
    if (isset($_POST['new']) && $_POST['new'] == 1) {
        $id = $_REQUEST['id'];
        $full_name = $_REQUEST['full_name'];
        $username = $_REQUEST['username'];
        $email = $_REQUEST['email'];
        $gender = $_REQUEST['gender'];
        $password = $_REQUEST['password'];
        $update = "UPDATE teacher SET full_name='" . $full_name . "',username ='" . $username . "', email='" . $email . "', gender='" . $gender . "', password='" . md5($password) . "' WHERE id='" . $id . "'";
        mysqli_query($con, $update) or die(mysqli_error($con));
        $status = "Teacher List Updated Successfully. </br></br>
<a href='ViewTeacherList.php'>View Updated Record</a>";
        echo '<p style="color:#008000;">' . $status . '</p>';
    } else {
    ?>
        <form name="form" method="post" action="">
            <input type="hidden" name="new" value="1" />
            <input name="id" type="hidden" value="<?php echo $row['id']; ?>" />
            <p><input type="text" name="full_name" placeholder="Update Full Name"
                    required value="<?php echo $row['full_name']; ?>" /></p>
            <p><input type="text" name="username" placeholder="Update UserName"
                    required value="<?php echo $row['username']; ?>" /></p>
            <p><input type="text" name="email" placeholder="Update Email"
                    required value="<?php echo $row['email']; ?>" /></p>
            <p>
                Gender:
                <input type="radio" name="gender" value="Male" <?php if ($row['gender'] == 'Male') echo 'checked'; ?> required> Male
                <input type="radio" name="gender" value="Female" <?php if ($row['gender'] == 'Female') echo 'checked'; ?> required> Female
            </p>

            <p><input type="text" name="password" placeholder="Update Password"
                    required value="<?php echo $row['password']; ?>" /></p>
            <p><input name="submit" type="submit" value="Update" /></p>
        </form>
    <?php } ?>
</body>

</html>