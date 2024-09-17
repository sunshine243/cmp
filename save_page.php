<?php
session_start();
include('auth.php');
include 'database.php';

if (isset($_SESSION['role']) && $_SESSION['role'] == "teacher") {
    $name = $_SESSION['role'];
} elseif (isset($_SESSION['role']) && $_SESSION['role'] == "admin") {
    $name = $_SESSION['role'];
}

if(isset($_SESSION['msg']))
unset($_SESSION['msg']);
if(isset($_SESSION['POST']))
unset($_SESSION['POST']);
$current_name = $_POST['filename'];
$new_name = $_POST['fname'];
$content = $_POST['content'];
$i = 0;
if(!is_dir("./pages"))
    mkdir("./pages");
if($current_name != $new_name){
    $nname = $new_name;
    while(true){
        if(is_file("./pages/{$nname}.html")){
            $nname = $new_name."_".($i++);
        }else{
            break;
        }
    }
    $new_name = $nname;
}
if(!empty($current_name) && $current_name != $new_name){
    rename("./pages/{$current_name}.html","./pages/{$new_name}.html");
}
$save = file_put_contents("./pages/{$new_name}.html",$content);
if ($save > 0) {
    if ($name == 'teacher') {
        $redirectUrl = './teacher/teacher_index.php';
    } elseif ($name == 'admin') {
        $redirectUrl = './admin/admin_index.php';
    }
    echo "<script>
        alert('Page Content Successfully Saved.');
        window.location.href = '$redirectUrl';
    </script>";
} else {
    echo "<script>
        alert('Page Content has failed to save.');
        window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
    </script>";
}

?>