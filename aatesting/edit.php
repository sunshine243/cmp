<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pageName = $_POST['pageName'];
    $content = $_POST['content'];

    // 保存新的内容到页面文件中
    file_put_contents($pageName, $content);

    header("Location: $pageName");
    exit;
}

// 获取页面名称和当前内容
if (isset($_GET['page'])) {
    $pageName = $_GET['page'];
    if (file_exists($pageName)) {
        $content = file_get_contents($pageName);
    } else {
        echo "Page not found!";
        exit;
    }
} else {
    echo "No page specified!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Page</title>
</head>
<body>
    <h1>Edit Page: <?php echo $pageName; ?></h1>
    <form method="post" action="edit.php">
        <input type="hidden" name="pageName" value="<?php echo $pageName; ?>">
        <textarea name="content" rows="20" cols="80"><?php echo htmlspecialchars($content); ?></textarea><br>
        <button type="submit">Save Changes</button>
    </form>
    <a href="<?php echo $pageName; ?>">Back to Page</a>
</body>
</html>
