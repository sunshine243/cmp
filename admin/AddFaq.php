<?php
session_start();
include('auth.php');
include 'database.php';

if (!isset($_SESSION['id'])) {
	header("Location: admin_login.php");
	exit();
}
$status = "";

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


// 处理表单提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$question = $_POST['question'];
	$answer = $_POST['answer'];

	// 插入数据到数据库
	$sql = "INSERT INTO faqs (question, answer) VALUES (?, ?)";
	$stmt = $con->prepare($sql);
	$stmt->bind_param("ss", $question, $answer);

	if ($stmt->execute()) {
		echo "FAQ 已成功添加!";
	} else {
		echo "Error: " . $sql . "<br>" . $con->error;
	}

	$stmt->close();
}

// 获取所有的FAQs
$sql = "SELECT * FROM faqs";
$result = $con->query($sql);
$faqs = [];

if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
		$faqs[] = $row;
	}
} else {
	echo "没有FAQ记录";
}

$con->close();
?>


<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
	<script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
	<link href="../css/index.css" rel="stylesheet" />


	<!-- include bootstrap, font awesome and rich text library CSS -->
	<link rel="stylesheet" type="text/css" href="../bootstrap-5.3.3-dist/css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="../font-awesome/css/font-awesome.css" />
	<link rel="stylesheet" type="text/css" href="../richtext/richtext.min.css" />

	<!-- include jquer, bootstrap and rich text JS -->
	<script src="../bootstrap-5.3.3-dist/js/jquery-3.3.1.min.js"></script>
	<script src="../bootstrap-5.3.3-dist/js/bootstrap.js"></script>
	<script src="../richtext/jquery.richtext.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

	<script>
		$(document).ready(function() {
			$(".arrow").click(function() {
				$(this).find("i").toggleClass("fa-chevron-down fa-chevron-up");
				$(this).siblings(".accordion").slideToggle();
			});

			$(".siderbar_menu li").click(function() {
				// Check if the current item is active
				var isActive = $(this).hasClass("active");

				// Remove 'active' class from all items
				$(".siderbar_menu li").removeClass("active");
				// Close all accordions
				$(".accordion").slideUp();
				// Change all arrow icons to 'fa-chevron-down'
				$(".arrow i").removeClass("fa-chevron-up").addClass("fa-chevron-down");

				// If the clicked item was not active, make it active and open its accordion
				if (!isActive) {
					$(this).addClass("active");
					$(this).find(".accordion").slideDown();
					$(this).find(".arrow i").toggleClass("fa-chevron-down fa-chevron-up");
				}
			});

			$(".hamburger").click(function() {
				$(".wrapper").addClass("active");
			});

			$(".close, .bg_shadow").click(function() {
				$(".wrapper").removeClass("active");
			});
		});
	</script>
	<title>Add School</title>
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
						<a href="admin_index.php">
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
							<li><a href="AddSchool.php" class="active"><i class="fas fa-user-plus pr-1"></i>Add School</a></li>
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
						<a href="quiz.php">
							<div class="icon"><i class="fas fa-clipboard-list"></i></div>
							<div class="title">Quiz</div>
						</a>
					</li>
					<li>
						<a href="upload_image.php">
							<div class="icon"><i class="fas fa-calendar-alt"></i></div>
							<div class="title">Profile</div>
						</a>
					</li>
				</ul>
				<div class="logout_btn">
					<?php
					if (isset($_SESSION['username'])) {
						echo '<a href="logout.php">Logout</a>';
					} else {
						// 如果没有设置用户名会话，则显示登录按钮或其他登录相关的内容
						// 这里可以根据需要添加适当的登录按钮或链接
						echo '<a href="admin_login.php">Logout</a>';
					}
					?>
				</div>

			</div>
		</div>

		<div class="main_container">
			<div class="navbar">
				<div class="hamburger">
					<i class="fas fa-bars"></i>
				</div>
				<div class="logo">
					<a href="dashboard.php">User Dashboard</a>
					<a href="ViewSchoolList.php">View School List</a>
				</div>
			</div>

			<div class="content">
				<h1>Add New FAQ</h1>
				<div class="container" style="margin-top: 50px; margin-bottom: 50px;">
					<div class="row">
						<div class="offset-md-3 col-md-6">
							<h1 class="text-center">Add FAQ</h1>

							<!-- for to add FAQ -->
							<form method="POST" action="AddFaq.php">

								<!-- question -->
								<div class="form-group">
									<label>Enter Question</label>
									<input type="text" name="question" class="form-control" required />
								</div>

								<!-- answer -->
								<div class="form-group">
									<label>Enter Answer</label>
									<textarea name="content" id="summernote" class="summernote" required></textarea>
								</div>

								<!-- submit button -->
								<input type="submit" name="submit" class="btn btn-info" value="Add FAQ" />
							</form>
						</div>
					</div>

					<!-- show all FAQs added -->
					<div class="row">
						<div class="offset-md-2 col-md-8">
							<table class="table table-bordered">
								<!-- table heading -->
								<thead>
									<tr>
										<th>ID</th>
										<th>Question</th>
										<th>Answer</th>
										<th>Actions</th>
									</tr>
								</thead>

								<!-- table body -->
								<tbody>
									<?php foreach ($faqs as $faq): ?>
										<tr>
											<td><?php echo $faq["id"]; ?></td>
											<td><?php echo $faq["question"]; ?></td>
											<td><?php echo $faq["answer"]; ?></td>
											<td>
												<!-- edit button -->
												<a href="edit.php?id=<?php echo $faq['id']; ?>" class="btn btn-warning btn-sm">
													Edit
												</a>

												<!-- delete form -->
												<form method="POST" action="delete.php" onsubmit="return confirm('Are you sure you want to delete this FAQ ?');">
													<input type="hidden" name="id" value="<?php echo $faq['id']; ?>" required />
													<input type="submit" value="Delete" class="btn btn-danger btn-sm" />
												</form>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
					<script>
						$('#summernote').summernote({
							placeholder: 'Hello stand alone ui',
							tabsize: 2,
							height: 120,
							toolbar: [
								['style', ['style']],
								['font', ['bold', 'underline', 'clear']],
								['color', ['color']],
								['para', ['ul', 'ol', 'paragraph']],
								['table', ['table']],
								['insert', ['link', 'picture', 'video']],
								['view', ['fullscreen', 'codeview', 'help']]
							]
						});
					</script>
				</div>
			</div>
		</div>
	</div>
</body>


</html>