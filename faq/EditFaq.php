<?php
session_start();
include('../auth.php');
include '../database.php';

if (isset($_SESSION['role']) && $_SESSION['role'] == "teacher") {
	$name = $_SESSION['role'];
} elseif (isset($_SESSION['role']) && $_SESSION['role'] == "admin") {
	$name = $_SESSION['role'];
}


$userId = $_SESSION['id'];
$query = "SELECT username, profile_image FROM " . $name . " WHERE id='$userId'";
$result = mysqli_query($con, $query);


if ($result && mysqli_num_rows($result) > 0) {
	$row = mysqli_fetch_assoc($result);
	$username = $row['username']; // 从数据库中获取用户名
	if (isset($row['profile_image']) && !empty($row['profile_image'])) {
		if ($name == 'admin') {
			$profileImage = str_replace('./', './', $row['profile_image']);
		} else if ($name == 'teacher') {
			$profileImage = str_replace('./', './', $row['profile_image']);
		}
	} else {
		$profileImage = '../image/default_profile_image.png';
	}
} else {
	// 如果查询失败或者没有找到用户，则使用默认图片并处理错误
	$username = "Unknown User";
	$profileImage = '../image/default_profile_image.png';  //default image
}
// Check if FAQ exists
$id = $_REQUEST['id'];
$sql = "SELECT * FROM faqs WHERE id = ?";
$statement = $con->prepare($sql);
$statement->bind_param("i", $id);
$statement->execute();
$result = $statement->get_result();
$faq = $result->fetch_assoc();

if (!$faq) {
	die("FAQ not found");
}

// Processing the FAQ update in EditFaq.php
if (isset($_POST["submit"])) {
	$question = mysqli_real_escape_string($con, $_POST['question']);
	$answer = $_POST['answer']; // Don't escape here as the content might include HTML

	// Optionally, you can process the $answer here to ensure image URLs are consistent
	// For example, make sure all image paths are relative to the document root

	// Update the FAQ in the database
	$sql = "UPDATE faqs SET question = ?, answer = ? WHERE id = ?";
	$statement = $con->prepare($sql);
	$statement->bind_param("ssi", $question, $answer, $id);

	if ($statement->execute()) {
		// Redirect back to the previous page after successful update
		header("Location: IndexFaq.php");
		exit();
	} else {
		echo "<p class='text-danger'>Error: " . $statement->error . "</p>";
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Edit FAQ</title>
	<link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../asset/summernote-lite.css">
	<script src="../asset/summernote-lite.js"></script>
	<script src="https://kit.fontawesome.com/b99e675b6e.js"></script>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<link href="../css/index.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
	<style>
		:root {
			--bs-success-rgb: 71, 222, 152 !important;
		}

		html,
		body {
			height: 100%;
			width: 100%;
			font-family: Apple Chancery, cursive;
		}

		.btn-info.text-light:hover,
		.btn-info.text-light:focus {
			background: #000;
		}
	</style>
</head>

<body>
	<div class="wrapper">

		<div class="sidebar">
			<div class="bg_shadow"></div>
			<div class="sidebar_inner">
				<div class="close">
					<i class="fas fa-times"></i>
				</div>

				<div class="profile_info">
					<div class="profile_img">
						<img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Image">
					</div>
					<div class="profile_data">
						<p class="name"><?php echo htmlspecialchars($username); ?></p>
						<span><i class="fas fa-map-marker-alt"></i>Kampar, Perak</span>
					</div>
				</div>

				<ul class="siderbar_menu">
						<li>
							<a href="../<?php echo $name; ?>/<?php echo $name; ?>_index.php">
								<div class="icon"><i class="fas fa-home"></i></div>
								<div class="title">Home</div>
							</a>
						</li>
						<li>
							<a href="#">
								<div class="icon"><i class="fas fa-hotel"></i></div>
								<div class="title">School</div>
								<div class="arrow"><i class="fas fa-chevron-down"></i></div>
							</a>
							<ul class="accordion">
								<?php
								if ($name == "admin") {
									echo '<li><a href="../admin/AddSchool.php" class="active"><i class="fas fa-user-plus pr-1"></i>Add School</a></li>';
								} elseif ($name == "teacher") {
									echo '<li><a href="../teacher/teacher_ViewSchool.php" class="active"><i class="fas fa-user-plus pr-1"></i>School List</a></li>';
								}
								?>

							</ul>
						</li>

						<li>
							<a href="#">
								<div class="icon"><i class="fas fa-user-tie"></i></div>
								<div class="title">Teachers</div>
								<div class="arrow"><i class="fas fa-chevron-down"></i></div>
							</a>
							<ul class="accordion">
								<li><a href="AddTeacher.php" class="active"><i class="fas fa-user-plus pr-1"></i>Add Teachers</a></li>
							</ul>
						</li>

						<li>
							<a href="#">
								<div class="icon"><i class="fas fa-user-graduate"></i></div>
								<div class="title">Students</div>
								<div class="arrow"><i class="fas fa-chevron-down"></i></div>
							</a>
							<ul class="accordion">
								<li><a href="AddStudent.php" class="active"><i class="fas fa-users pr-1"></i>Add Students</a></li>
							</ul>
						</li>
						<li>
							<a href="../<?php echo ($name === 'student') ? $name . '/quiz_home.php' : $name . '/dashboard.php'; ?>">
								<div class="icon"><i class="fas fa-clipboard-list"></i></div>
								<div class="title">Quiz</div>
							</a>
						</li>

						<li>
							<a href="IndexFaq.php">
								<div class="icon"><i class="fas fa-info-circle"></i></div>
								<div class="title">FAQ</div>
							</a>
						</li>
						<li>
							<a href="../<?php echo htmlspecialchars($name); ?>/upload_image.php">
								<div class="icon"><i class="fas fa-calendar-alt"></i></div>
								<div class="title">Profile</div>
							</a>
						</li>
						<li>
							<a href="../<?php echo $name; ?>/<?php echo $name; ?>_login.php">
								<div class="icon"><i class="fas fa-sign-out-alt"></i></div>
								<div class="logout_btn">Logout</div>
							</a>
						</li>
					</ul>

			</div>
		</div>
		<div class="main_container">
			<div class="navbar">
				<div class="hamburger">
					<i class="fas fa-bars"></i>
				</div>
				<div class="logo">
					<a href="../<?php echo $name; ?>/<?php echo $name; ?>_index.php">Home</a>
					<a href="../<?php echo $name; ?>/<?php echo $name; ?>_login.php">Logout</a>
				</div>
			</div>
			<div class="container" style="margin-top: 50px; margin-bottom: 50px;">
				<div class="row">
					<div class="offset-md-3 col-md-6">
						<h1 class="text-center">Edit FAQ</h1>

						<form method="POST" action="EditFaq.php?id=<?php echo $faq['id']; ?>">

							<input type="hidden" name="id" value="<?php echo $faq['id']; ?>" />

							<div class="form-group">
								<label>Enter Question</label>
								<input type="text" name="question" class="form-control" value="<?php echo htmlspecialchars($faq['question']); ?>" required />
							</div>

							<div class="form-group">
								<label>Enter Answer</label>
								<textarea name="answer" id="summernote" class="form-control" required><?php echo htmlspecialchars($faq['answer']); ?></textarea>
							</div>

							<input type="submit" name="submit" class="btn btn-warning" value="Edit FAQ" />
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		$(document).ready(function() {
			$('#summernote').summernote({
				height: 300,
				callbacks: {
					onImageUpload: function(files) {
						var data = new FormData();
						data.append('file', files[0]);

						$.ajax({
							url: 'upload_image_faq.php',
							method: 'POST',
							data: data,
							contentType: false,
							processData: false,
							success: function(response) {
								var result = JSON.parse(response);
								if (result.url) {
									$('#summernote').summernote('insertImage', result.url);
								} else {
									console.error('Upload failed:', result.error);
								}
							},
							error: function(xhr, status, error) {
								console.error('Upload error:', status, error);
							}
						});
					}
				}
			});
		});
	</script>

</body>

</html>