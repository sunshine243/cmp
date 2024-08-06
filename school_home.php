<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">        <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link href="../css/index.css" rel="stylesheet" />

    <script src="https://cdn.tiny.cloud/1/7d0zm8nmxksb3lzqrghr4gc23h8dcqc9n0981y2nveackwgu/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">  
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
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
<title>School Home Page</title>
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
                        <img src="image/default_profile_image.png" alt="default_profile_image">
                        </div>
                        <div class="profile_data">
                            <p class="name">teacher_view</p>
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
                            <a href="Faq.php">
                                <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                                <div class="title">FAQ</div>
                            </a>
                        </li>
                        <li>
                            <a href="Profile.php">
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
                            echo '<a href="teacher_login.php">Logout</a>';
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
                        <a href="ViewTeacherList.php">View Teacher List</a>
                    </div>
                </div>

                <div class="content">
                <script> 
                    tinymce.init({
                        selector: 'textarea',
                        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage advtemplate ai mentions tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss markdown',
                        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                        tinycomments_mode: 'embedded',
                        tinycomments_author: 'Author name',
                        mergetags_list: [
                        { value: 'First.Name', title: 'First Name' },
                        { value: 'Email', title: 'Email' },
                        ],
                        ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
                    });
                </script>
                <textarea>
                    Here to create content！
                </textarea>
            </div>               
</body>

</html>

