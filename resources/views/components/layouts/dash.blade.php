<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>JayMatian Mineral Laboratory System</title>

	<!-- <base href="../"> -->

	<link rel="stylesheet" href="_assets/_vendor/BS4/css/bootstrap.min.css">
	<link rel="stylesheet" href="_assets/css/fa-all.css">
	<link rel="stylesheet" href="_assets/css/w3.css">
	<link rel="stylesheet" href="_assets/css/coryG_UIOps.css">
	<link rel="stylesheet" href="_assets/css/coryG_base.css">
	<link rel="stylesheet" href="_assets/css/fonts.css">
	<link rel="stylesheet" href="_assets/css/animate.css">

	{{-- <link rel="stylesheet" type="text/css" href="_assets/css/inter_slop.css"> --}}
	<link rel="stylesheet" type="text/css" href="_assets/css/merge_slop.css">

	<script src="_assets/js/jquery-3.6.0.min.js"></script>
	<script src="_assets/js/popper.min.js"></script>
	<script src="_assets/_vendor/BS4/js/bootstrap.bundle.min.js"></script>
	<script src="_assets/js/chart.js"></script>
	<script src="_assets/js/SuperScript.js"></script>
	<script src="_assets/js/toappend.js"></script>

    <script src="_assets/js/anims.js"></script>
	<script src="_assets/js/coryG_UIOps.js"></script>
	<script src="_assets/js/customalerter.js"></script>

	<script src="_assets/js/app.js"></script>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="#" class="sidebar-brand">
                <i class="fas fa-user-graduate"></i>
                <span>MyAdmin</span>
            </a>
        </div>

        <ul class="sidebar-menu">
            <li>
                <a href="dash2.html">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#" class="active">
                    <i class="fas fa-user-graduate"></i>
                    <span>Students</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Teachers</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-users"></i>
                    <span>Parents</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-book"></i>
                    <span>Library</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-calendar-check"></i>
                    <span>Attendance</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-file-alt"></i>
                    <span>Exam</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-bed"></i>
                    <span>Hostel</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-user-circle"></i>
                    <span>Account</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </div>
    {{ $slot }}

    @include('pieces.commons')
</body>
</html>
