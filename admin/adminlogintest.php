<?php

// connect with database
include 'database.php';

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
?>


<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">  </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
	
    <script>
            $(document).ready(function () {
                $(".arrow").click(function () {
                    $(this).find("i").toggleClass("fa-chevron-down fa-chevron-up");
                    $(this).siblings(".accordion").slideToggle();
                });

                $(".siderbar_menu li").click(function () {
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

                $(".hamburger").click(function () {
                    $(".wrapper").addClass("active");
                });

                $(".close, .bg_shadow").click(function () {
                    $(".wrapper").removeClass("active");
                });
            });
        </script>

<style>
        body {
            background: #e7f1ff;
            font-size: 14px;
            letter-spacing: 1px;
        }

        .content {
            padding: 20px;
            flex-direction: column;
            align-items: flex-start;
            display: flex;
            flex-wrap: wrap;
        }

        .content .h1 {
            margin-bottom: 20px;
        }

        .accordion {
            background: #6f6fc7;
            padding-left: 20px;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .accordion li a {
            display: block;
            color: #c4dcff;
            padding: 12px 0;
            padding-left: 10px;
        }

        .accordion li a:hover {
            background: #3d3d79;
            color: #fff;
        }

        .accordion li a.active {
            color: #fff;
            text-decoration: none;
        }

        .siderbar_menu > li.active .accordion {
            height: auto;
        }

        .siderbar_menu > li.active .arrow {
            transform: rotate(180deg);
            transition: all 0.3s ease;
        }

        @media (max-width: 1024px) {
            .content .item {
                width: 47%;
            }

            .wrapper.active .sidebar_inner {
                left: 0;
                transition: all 0.5s ease;
            }

            .wrapper.active .sidebar .bg_shadow {
                visibility: visible;
                opacity: 0.7;
            }
        }

        @media (max-width: 528px) {
            .content .item {
                width: 100%;
            }
        }
    </style>
    <title>Add School</title>
</head>

<body>
<form id="form1" runat="server">
        <div class="wrapper">

        
<!-- show all FAQs in a panel -->
<div class="container" style="margin-top: 50px; margin-bottom: 50px;">
    <div class="accordion" id="accordionExample">
        <?php foreach ($faqs as $faq): ?>
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading-<?php echo $faq['id']; ?>">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $faq['id']; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $faq['id']; ?>">
                    <?php echo $faq['question']; ?>
                </button>
            </h2>
            <div id="collapse-<?php echo $faq['id']; ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?php echo $faq['id']; ?>" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <?php echo $faq['answer']; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

</div>
</form>
</body>

</html>