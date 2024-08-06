<?php
//include("auth.php");
require('database.php');
$id=$_REQUEST['id'];
$query = "SELECT * FROM school where id='".$id."'";
$result = mysqli_query($con, $query) or die ( mysqli_error($con));
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Update School List</title>
</head>
<body>
<p><a href="dashboard.php">User Dashboard</a>
| <a href="AddSchool.php">Add New School</a>
| <a href="logout.php">Logout</a></p>
<h1>Update School List</h1>
<?php
$status = "";
if(isset($_POST['new']) && $_POST['new']==1)
{
$id=$_REQUEST['id'];
$school_name =$_REQUEST['school_name'];
$update="UPDATE school set school_name='".$school_name."' where id='".$id."'";
mysqli_query($con, $update) or die(mysqli_error($con));
$status = "School List Updated Successfully. </br></br>
<a href='ViewSchoolList.php'>View Updated Record</a>";
echo '<p style="color:#008000;">'.$status.'</p>';
}else {
?>
<form name="form" method="post" action="">
<input type="hidden" name="new" value="1" />
<input name="id" type="hidden" value="<?php echo $row['id'];?>" />
<p><input type="text" name="school_name" placeholder="Update School Name"
required value="<?php echo $row['school_name'];?>" /></p>
<p><input name="submit" type="submit" value="Update" /></p>
</form>
<?php } ?>
</body>
</html>