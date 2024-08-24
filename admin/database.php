<?php
$con = mysqli_connect("yau-fyp.cdqemagqeauy.ap-southeast-2.rds.amazonaws.com","admin","admin123","cmp",3306); // 将localhost改为新host地址
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>
