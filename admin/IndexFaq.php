
<?php
session_start();
include('auth.php');
include 'database.php';

if (!isset($_SESSION['id'])) {
	header("Location: admin_login.php");
	exit();
}

$userId = $_SESSION['id'];
$query = "SELECT username, profile_image FROM admin WHERE id='$userId'";
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
	$row = mysqli_fetch_assoc($result);
	$username = $row['username'];
	$profileImage = !empty($row['profile_image']) ? $row['profile_image'] : '../image/default_profile_image.png';
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
<html>

<head>
	<meta charset="UTF-8">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
	<title>FAQ's List</title>
</head>

<body>
	<div class="wrapper">
		<div class="sidebar">
			<!-- Sidebar content here -->
		</div>
		<div class="main_container">
			<div class="navbar">
				<!-- Navbar content here -->
			</div>
			<div class="content">
				<h1>Question</h1>
				<div class="container" style="margin-top: 50px; margin-bottom: 50px;">
					<div class="accordion" id="accordionExample">
						<?php foreach ($faqs as $faq): ?>
							<div class="accordion-item">
								<h2 class="accordion-header" id="heading-<?php echo $faq['id']; ?>">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $faq['id']; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $faq['id']; ?>">
										<?php echo htmlspecialchars($faq['question']); ?>
									</button>
								</h2>
								<div id="collapse-<?php echo $faq['id']; ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?php echo $faq['id']; ?>" data-bs-parent="#accordionExample">
									<div class="accordion-body">
										<?php echo htmlspecialchars($faq['answer']); ?>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>
