<!DOCTYPE html>

<html>
<head>
    <title>School Management Project</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">        <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link href="index.css" rel="stylesheet" />

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
                            <img src="../Image/b2.jpg" alt="profile_img">
                        </div>
                        <div class="profile_data">
                            <p class="name">Admin Module</p>
                            <span><i class="fas fa-map-marker-alt"></i>Kampar, Perak</span>
                        </div>
                    </div>

                    <ul class="siderbar_menu">
                        <li>
                            <a href="../Admin/AdminHome.aspx">
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
                                <li><a href="../Admin/Teacher.aspx" class="active"><i class="fas fa-user-plus pr-1"></i>Add Teachers</a></li>
                                <li><a href="../Admin/TeacherSubject.aspx" class="active"><i class="fas fa-book-reader pr-1"></i>Teachers Subject</a></li>
                                <li><a href="../Admin/Expense.aspx" class="active"><i class="fas fa-hand-holding-usd pr-1"></i>Add Expense</a></li>
                                <li><a href="../Admin/ExpenseDetails.aspx" class="active"><i class="fas fa-money-check-alt pr-1"></i>Expense Details</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">
                                <div class="icon"><i class="fas fa-user-graduate"></i></div>
                                <div class="title">Students</div>
                                <div class="arrow"><i class="fas fa-chevron-down"></i></div>
                            </a>
                            <ul class="accordion">
                                <li><a href="../Admin/Student.aspx" class="active"><i class="fas fa-users pr-1"></i>Add Students</a></li>
                                <li><a href="../Admin/StudAttendanceDetails.aspx" class="active"><i class="far fa-list-alt pr-1"></i>Attendance Details</a></li>
                            </ul>

                        </li>
                        <li>
                            <a href="#">
                                <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                                <div class="title">FAQ</div>
                                <div class="arrow"><i class="fas fa-chevron-down"></i></div>
                            </a>
                            <ul class="accordion">
                                <li><a href="../Admin/Marks.aspx" class="active"><i class="fas fa-notes-medical pr-1"></i>Add Marks</a></li>
                                <li><a href="../Admin/MarkDetails.aspx" class="active"><i class="fas fa-clipboard-check pr-1"></i>Marks Details</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">
                                <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                                <div class="title">Profile</div>
                                <div class="arrow"><i class="fas fa-chevron-down"></i></div>
                            </a>
                            <ul class="accordion">
                                <li><a href="../Admin/EmployeeAttendance.aspx" class="active"><i class="fas fa-calendar-plus pr-1"></i>Employee Attendance</a></li>
                                <li><a href="../Admin/EmpAttendanceDetails.aspx" class="active"><i class="fas fa-calendar-check pr-1"></i>Employee Details</a></li>
                            </ul>
                        </li>
                    </ul>
                    <div class="logout_btn">
                        <asp:LinkButton ID="btnLogOut" runat="server" CausesValidation="false">Logout</asp:LinkButton>
                    </div>

                </div>
            </div>
            <div class="main_container">
                <div class="navbar">
                    <div class="hamburger">
                        <i class="fas fa-bars"></i>
                    </div>
                    <div class="logo">
                        <a href="#">Content Management Portal for IT program in Primary School</a>
                    </div>
                </div>

                <asp:ContentPlaceHolder ID="ContentPlaceHolder1" runat="server">
                </asp:ContentPlaceHolder>

            </div>
        </div>

    </form>
</body>
</html>
