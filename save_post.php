<?php
session_start();
include('auth.php');
include 'database.php';


if (isset($_SESSION['role']) && $_SESSION['role'] == "teacher") {
    $name = $_SESSION['role'];
} elseif (isset($_SESSION['role']) && $_SESSION['role'] == "admin") {
    $name = $_SESSION['role'];
}

// Retrieve the school_name from the URL
if (isset($_GET['school_name'])) {
    $school_name = htmlspecialchars($_GET['school_name']);
    // Now you can use $school_name to identify the school
    echo "Adding a post for: " . $school_name;
} else {
    // Handle the case where school_name is not provided
    // echo "School name is not specified.";
}

if(isset($_SESSION['msg']))
unset($_SESSION['msg']);
if(isset($_SESSION['POST']))
unset($_SESSION['POST']);
$current_name = $_POST['filename'];
$new_name = $_POST['fname'];
$content = $_POST['content'];
$i = 0;
if(!is_dir("./schools/$school_name/post"))
    mkdir("./schools/$school_name/post");
if($current_name != $new_name){
    $nname = $new_name;
    while(true){
        if(is_file("./schools/$school_name/post/{$nname}.html")){
            $nname = $new_name."_".($i++);
        }else{
            break;
        }
    }
    $new_name = $nname;
}
if(!empty($current_name) && $current_name != $new_name){
    rename("./schools/$school_name/post/{$current_name}.html","./pages/{$new_name}.html");
}
$save = file_put_contents("./schools/$school_name/post/{$new_name}.html",$content);
if ($save > 0) {
        $redirectUrl = './schools/'.$school_name.'/'.$school_name.'.php';    
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