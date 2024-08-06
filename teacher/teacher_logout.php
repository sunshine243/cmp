<?php
session_start();
if(session_destroy())
{
header("Location: teacher_login.php");
exit();
}
?>