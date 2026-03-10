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

	<link rel="stylesheet" type="text/css" href="_assets/css/inter_slop.css">
	<!-- <link rel="stylesheet" type="text/css" href="_assets/css/kimi_slop.css"> -->

	<script src="_assets/js/jquery-3.6.0.min.js"></script>
	<script src="_assets/js/popper.min.js"></script>
	<script src="_assets/_vendor/BS4/js/bootstrap.bundle.min.js"></script>
	<script src="_assets/js/chart.js"></script>
	<script src="_assets/js/SuperScript.js"></script>
	<script src="_assets/js/toappend.js"></script>

    <script src="_assets/js/anims.js"></script>
	<script src="_assets/js/coryG_UIOps.js"></script>
	<script src="_assets/js/customalerter.js"></script>
	<script src="_assets/js/super_encryptor.js"></script>

	<script src="_assets/js/app.js"></script>
</head>
<body>
    {{ $slot }}

    @auth
		{{-- load more stuff --}}
		<!-- only shows up when youre logged in -->
		@include('pieces.commons')
    @endauth
</body>
</html>
