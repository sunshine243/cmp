<?php
//include("auth.php");
require('database.php');
$status = "";
?>

<!DOCTYPE html>
<html>
<head>
<<meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">        <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link href="index.css" rel="stylesheet" />

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">  
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

        
<title>Add School</title>
</head>

<body>
<form id="form1" runat="server">
        <div class="wrapper"></div>

            <div class="main_container">
                <div class="navbar">
                    <div class="hamburger">
                        <i class="fas fa-bars"></i>
                    </div>
                    <div class="logo">
                        <a href="dashboard.php">User Dashboard</a>
                        <a href="ViewSchoolList.php">View School List</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>

                <div class="content">
                <p><a href="dashboard.php">User Dashboard</a>
                    <a href="ViewSchoolList.php">View School Records</a>
                    | <a href="logout.php">Logout</a></p>
                    <h1>Insert New School</h1>
                    <form name="form" method="post" action="">
                    <input type="hidden" name="new" value="1" />
                    <p><input type="text" name="school_name" placeholder="Enter School Name" required
                    /></p>
                    <p><input name="submit" type="submit" value="Submit" /></p>
                    </form>
                    <p style="color:#008000;"><?php echo $status; ?></p>
                    <? if(isset($_POST['new']) && $_POST['new']==1){
 $school_name =$_REQUEST['school_name'];
 $ins_query="INSERT into school
 (`school_name`)values
 ('$school_name')";

 mysqli_query($con,$ins_query)
 or die(mysqli_error($con));
 $status = "New School Inserted Successfully.
 </br></br><a href='ViewSchoolList.php'>View School List</a>";
}?>
                </div>                    
            </div>
        </div>
    </form>


</body>
</html>