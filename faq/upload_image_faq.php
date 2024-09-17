<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../faq_image/'; // Change this to your desired directory
    $uploadFile = $uploadDir . basename($_FILES['file']['name']);

    // Move the uploaded file to the specified directory
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        // Return the file URL as a JSON response
        echo json_encode(['url' => $uploadFile]);
    } else {
        echo json_encode(['error' => 'Failed to move uploaded file.']);
    }
} else {
    echo json_encode(['error' => 'File upload error.']);
}
?>
