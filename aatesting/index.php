<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page List</title>
</head>
<body>
    <h1>List of Pages</h1>
    <ul>
        <?php
        $listFile = 'list.txt';

        if (file_exists($listFile)) {
            $lines = file($listFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                list($name, $pageName) = explode('|', $line);
                // 将名字作为链接，点击后直接进入页面
                echo "<li><a href='$pageName'>$name</a> | <a href='edit.php?page=$pageName'>Edit</a></li>";
            }
        } else {
            echo "<li>No pages created yet.</li>";
        }
        ?>
    </ul>
    <a href="create.php">Create a New Page</a>
</body>
</html>
