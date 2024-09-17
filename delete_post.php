<?php
session_start();

// Clear session messages
if (isset($_SESSION['msg'])) unset($_SESSION['msg']);
if (isset($_SESSION['POST'])) unset($_SESSION['POST']);

// Retrieve the school_name from the URL and sanitize
if (isset($_GET['school_name']) && !empty($_GET['school_name'])) {
    $school_name = htmlspecialchars($_GET['school_name'], ENT_QUOTES, 'UTF-8');
} else {
    $_SESSION['msg']['type'] = 'danger';
    $_SESSION['msg']['text'] = 'School name is not specified.';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

// Retrieve the page from the URL and sanitize
if (isset($_GET['page']) && !empty($_GET['page'])) {
    $page = basename($_GET['page']); // Prevent directory traversal
} else {
    $_SESSION['msg']['type'] = 'danger';
    $_SESSION['msg']['text'] = 'Page is not specified.';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

// Construct the file path
$file_path = "./schools/" . $school_name . "/post/" . $page;

// Check if the file exists and attempt to delete it
if (is_file($file_path)) {
    if (unlink($file_path)) {
        $_SESSION['msg']['type'] = 'success';
        $_SESSION['msg']['text'] = 'Page content successfully deleted.';
    } else {
        $_SESSION['msg']['type'] = 'danger';
        $_SESSION['msg']['text'] = 'Failed to delete page content. Please check file permissions.';
    }
} else {
    $_SESSION['msg']['type'] = 'danger';
    $_SESSION['msg']['text'] = 'Page content not found.';
}

// Redirect back to the list page or wherever appropriate
header('Location: ./schools/' . strtolower($school_name) . '/' . strtolower($school_name) . '.php');
exit();
?>
