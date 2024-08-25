<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $pageName = strtolower(str_replace(' ', '_', $name)) . '.php';

    // 生成新页面
    $content = "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>$name</title>
</head>
<body>
    <h1>Welcome to $name page!</h1>
    <p>This is the auto-generated page for $name.</p>
</body>
</html>";

    file_put_contents($pageName, $content);

    // 保存到列表文件
    $listFile = 'list.txt';
    file_put_contents($listFile, $name . '|' . $pageName . "\n", FILE_APPEND);

    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Page</title>
</head>
<body>
    <h1>Create a New Page</h1>
    <form method="post" action="create.php">
        <label for="name">Enter the name of the page:</label>
        <input type="text" id="name" name="name" required>
        <button type="submit">Create</button>
    </form>
</body>
</html>
