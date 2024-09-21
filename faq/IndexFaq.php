<?php
session_start();
include('../auth.php');
include '../database.php';

if (isset($_SESSION['role']) && $_SESSION['role'] == "teacher") {
	$name = $_SESSION['role'];
} elseif (isset($_SESSION['role']) && $_SESSION['role'] == "admin") {
	$name = $_SESSION['role'];
} elseif (isset($_SESSION['role']) && $_SESSION['role'] == "student") {
	$name = $_SESSION['role'];
} else {
	$name = "Unknown";
}

$userId = $_SESSION['id'];
$query = "SELECT username, profile_image FROM " . $name . " WHERE id='$userId'";
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
	$row = mysqli_fetch_assoc($result);
	$username = $row['username'];
	if (isset($row['profile_image']) && !empty($row['profile_image'])) {
		$profileImage = $row['profile_image'];
	} else {
		$profileImage = '../image/default_profile_image.png';
	}
} else {
	$username = "Unknown User";
	$profileImage = '../image/default_profile_image.png';
}

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
	<script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
	<link href="../css/index.css" rel="stylesheet" />
	<link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="./asset/summernote-lite.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="./asset/summernote-lite.js"></script>
	<script src="./bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
	<title>FAQ Page</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			background-color: #e7f1ff;
			margin: 0;
			padding: 0;
		}

		.container1 {
			width: 96%;
			margin: 10px auto;
		}

		.accordion1 {
			background-color: #ffffff;
			border-radius: 8px;
			overflow: hidden;

		}

		.accordion-item {
			border-bottom: 1px solid #ddd;
			font-size: 18px;
		}

		.accordion-header1 {
			background-color: #5558c9;
			padding: 15px;
			cursor: pointer;
			color: white;
			font-weight: bold;
			position: relative;
			display: flex;
			align-items: center;
			justify-content: space-between;
		}

		.accordion-header1::after {
			content: '+';
			font-size: 20px;
			transition: transform 0.3s ease;
		}

		.accordion-header1.active::after {
			content: '-';
			transform: rotate(180deg);
		}


		.accordion-body {
			background-color: #ffffff;
			padding: 15px;
			display: none;
			font-size: 18px;
		}

		.accordion-body.show {
			display: block;
		}


		.accordion-header1.active+.accordion-body {
			display: block;
		}

		.header-container {
			display: flex;
			align-items: center;
			justify-content: space-between;
			margin-bottom: 20px;
		}

		.search-bar {
			padding: 8px;
			font-size: 16px;
			border: 1px solid #ddd;
			border-radius: 5px;
			width: 250px;
			margin: 10px;
		}
	</style>
	<script>
		$(document).ready(function() {
			$(".accordion-header").click(function() {
				$(this).toggleClass("active");
				$(this).next(".accordion-body").slideToggle();
			});

			$(".arrow").click(function() {
				$(this).find("i").toggleClass("fa-chevron-down fa-chevron-up");
				$(this).siblings(".accordion").slideToggle();
			});

			$(".siderbar_menu li").click(function() {
				var isActive = $(this).hasClass("active");
				$(".siderbar_menu li").removeClass("active");
				$(".accordion").slideUp();
				$(".arrow i").removeClass("fa-chevron-up").addClass("fa-chevron-down");

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

			$(".accordion-header1").click(function() {
				// 切换折叠内容的显示状态
				$(this).next(".accordion-body").toggleClass("show");

				// 切换活动状态
				$(this).toggleClass("active");

				// 同时切换其他所有折叠项的状态
				$(".accordion-header").not(this).removeClass("active");
				$(".accordion-body").not($(this).next(".accordion-body")).removeClass("show");
			});
		});
	</script>
</head>

<body>
	<form id="form1" runat="server">
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


						<?php if ($name != 'student'): ?>
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
						<?php else: ?>
							<li>
								<a href="../schools/<?php echo htmlspecialchars($folderName); ?>/<?php echo htmlspecialchars($folderName); ?>.php">
									<div class="icon"><i class="fas fa-hotel"></i></div>
									<div class="title"><?php echo htmlspecialchars($schoolName); ?></div> <!-- 动态显示学校名称 -->
								</a>
							</li>
						<?php endif; ?>


						<li>
							<a href="#">
								<div class="icon"><i class="fas fa-user-tie"></i></div>
								<div class="title">Teachers</div>
								<div class="arrow"><i class="fas fa-chevron-down"></i></div>
							</a>
							<ul class="accordion">
								<?php if ($name == 'admin'): ?>
									<li><a href="../admin/AddTeacher.php" class="active"><i class="fas fa-user-plus pr-1"></i>Add Teachers</a></li>
								<?php elseif ($name == 'teacher' || $name == 'student'): ?>
									<li><a href="../<?php echo $name; ?>/teacher_list.php" class="active"><i class="fas fa-users pr-1"></i>Teachers List</a></li>
								<?php endif; ?>
							</ul>
						</li>


						<?php if ($name != 'student'): ?>
							<li>
								<a href="#">
									<div class="icon"><i class="fas fa-user-graduate"></i></div>
									<div class="title">Students</div>
									<div class="arrow"><i class="fas fa-chevron-down"></i></div>
								</a>
								<ul class="accordion">
									<?php if ($name == 'admin'): ?>
										<li><a href="../admin/AddStudent.php" class="active"><i class="fas fa-users pr-1"></i>Add Students</a></li>
									<?php elseif ($name == 'teacher'): ?>
										<li><a href="../teacher/student_list.php" class="active"><i class="fas fa-users pr-1"></i>Students List</a></li>
									<?php endif; ?>
								</ul>
							</li>
						<?php endif; ?>


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

				<div class="content">
					<div class="container1">
						<h1>FAQ Page</h1>

						<?php if ($_SESSION['role'] == "admin" || $_SESSION['role'] == "teacher"): ?>
							<a href="AddFaq.php" style="display: inline-block; padding: 10px 20px; background-color: green; color: white; text-align: center; text-decoration: none; border-radius: 5px;">Add New FAQ</a>
						<?php endif; ?>
						<input type="text" id="liveSearch" class="search-bar" placeholder="Search...">
						<button type="button" class="btn btn-primary" onclick="window.location.href='../chatbot/index.php';">
							Open Chatbot
						</button>


						<div class="accordion1" id="accordionExample">
							<?php foreach ($faqs as $faq): ?>
								<div class="accordion-item">
									<h2 class="accordion-header1" id="heading-<?php echo $faq['id']; ?>">
										<button class="accordion-button collapsed" type="button" aria-expanded="false" aria-controls="collapse-<?php echo $faq['id']; ?>">
											<?php echo htmlspecialchars($faq['question']); ?>
										</button>
									</h2>
									<div id="collapse-<?php echo $faq['id']; ?>" class="accordion-body">
										<td><?php echo htmlspecialchars_decode($faq["answer"]); ?></td>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>

	<script>
		document.getElementById("liveSearch").addEventListener("keyup", function() {
			var filter = this.value.toLowerCase();
			var faqItems = document.querySelectorAll(".accordion-item");

			faqItems.forEach(function(item) {
				var question = item.querySelector(".accordion-header1 button").textContent.toLowerCase();
				if (question.indexOf(filter) > -1) {
					item.style.display = "";
				} else {
					item.style.display = "none";
				}
			});
		});
	</script>
</body>

</html>