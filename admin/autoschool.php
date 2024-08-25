<?php
session_start();
include('auth.php');
include 'database.php';

if (!isset($_SESSION['id'])) {
    header("Location: admin_login.php");
    exit();
}
$status = "";

if (isset($_POST['new']) && $_POST['new'] == 1) {
    $school_name = mysqli_real_escape_string($con, $_POST['school_name']);

    // 检查是否已经存在相同名称的学校
    $check_query = "SELECT * FROM school WHERE school_name = '$school_name'";
    $check_result = mysqli_query($con, $check_query);
    $num_rows = mysqli_num_rows($check_result);

    if ($num_rows > 0) {
        $status = "<span style='color: red; font-weight: bold;'>Error: School with the same name already exists.</span>";
    } else {
        // 如果不存在相同名称的学校，则插入新学校
        $ins_query = "INSERT INTO school (school_name) VALUES ('$school_name')";
        $result = mysqli_query($con, $ins_query);

        if ($result) {
            // 获取新插入学校的 ID
            $school_id = mysqli_insert_id($con);

            // 将内容写入新文件
            if (file_put_contents($file_path, $template)) {
                $status = "New School Inserted Successfully. </br></br><a href='ViewSchoolList.php'>View School List</a>";
            } else {
                $status = "School '$school_name' added successfully, but failed to create the page.";
            }
        } else {
            // Display error message if query fails
            $status = "Error: " . mysqli_error($con);
        }
    }
}

$userId = $_SESSION['id'];
$query = "SELECT username, profile_image FROM admin WHERE id='$userId'";
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $username = $row['username']; // 从数据库中获取用户名
    if (isset($row['profile_image']) && !empty($row['profile_image'])) {
        $profileImage = $row['profile_image'];
    } else {
        $profileImage = '../image/default_profile_image.png';
    }
} else {
    // 如果查询失败或者没有找到用户，则使用默认图片并处理错误
    $username = "Unknown User";
    $profileImage = '../image/default_profile_image.png';  //default image
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link href="../css/index.css" rel="stylesheet" />

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

    <title>Add School</title>
</head>

<body>
                <h1>Add New School</h1>
                <form name="form" method="POST" action="">
                    <input type="hidden" name="new" value="1" />
                    <p><input type="text" name="school_name" placeholder="Enter School Name" required /></p>
                    <p><input name="submit" type="submit" value="Add" /></p>
                </form>

                <?php
                if (isset($_POST['new']) && $_POST['new'] == 1) {
                    $school_name = $_POST['school_name'];

                    // 1. 插入学校到数据库
                    $query = "INSERT INTO school (school_name) VALUES ('$school_name')";
                    $result = mysqli_query($con, $query);

                    if ($result) {
                        // 2. 获取新插入学校的 ID
                        $school_id = mysqli_insert_id($con);

                        // 3. 创建对应的 PHP 页面
                        $file_name = strtolower(str_replace(' ', '_', $school_name)) . "_" . $school_id . ".php";
                        $file_path = "schools/" . $file_name; // 存放在 schools 目录下

                        // 4. 生成页面的内容
                        $template = "<?php\n";
                        $template .= "require_once '../db_connection.php';\n"; // 连接数据库
                        $template .= "\$school_id = $school_id;\n";
                        $template .= "\$query = \"SELECT * FROM school WHERE id = \$school_id LIMIT 1\";\n";
                        $template .= "\$result = mysqli_query(\$con, \$query);\n";
                        $template .= "\$school = mysqli_fetch_assoc(\$result);\n";
                        $template .= "if (\$school) {\n";
                        $template .= "    echo \"<h1>\" . \$school['school_name'] . \"</h1>\";\n";
                        $template .= "    echo \"<p>地址: \" . \$school['address'] . \"</p>\";\n";
                        $template .= "} else {\n";
                        $template .= "    echo \"学校不存在\";\n";
                        $template .= "}\n";
                        $template .= "?>";

                        // 5. 将内容写入新文件
                        if (file_put_contents($file_path, $template)) {
                            $status = "学校 '$school_name' 已成功添加，并且页面已创建。";
                        } else {
                            $status = "学校 '$school_name' 添加成功，但页面创建失败。";
                        }
                    } else {
                        $status = "添加学校失败。";
                    }
                }
                ?>

                <?php if (!empty($status)) { ?>
                    <p style="color:#008000;"><?php echo $status; ?></p>
                <?php } ?>
</body>
</html>