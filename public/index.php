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

	<link rel="stylesheet" type="text/css" href="_assets/css/slop_inter.css">
	<!-- <link rel="stylesheet" type="text/css" href="_assets/css/kimi_slop.css"> -->

	<script src="_assets/js/jquery-3.6.0.min.js"></script>
	<script src="_assets/_vendor/BS4/js/bootstrap.bundle.min.js"></script>
	<script src="_assets/js/chart.js"></script>
	<script src="_assets/js/SuperScript.js"></script>
	<script src="_assets/js/toappend.js"></script>

	<script src="_assets/js/coryG_UIOps.js"></script>
	<script src="_assets/js/customalerter.js"></script>
	<script src="_assets/js/app.js"></script>

	<style>
		/* HTML: <div class="loader"></div> */
		.loader {
			width: 50px;
			aspect-ratio: 1;
			display: grid;
			border: 4px solid #0000;
			border-radius: 50%;
			border-right-color: #25b09b;
			animation: l15 1s infinite linear;
		}
		.loader::before,
		.loader::after {    
			content: "";
			grid-area: 1/1;
			margin: 2px;
			border: inherit;
			border-radius: 50%;
			animation: l15 2s infinite;
		}
		.loader::after {
			margin: 8px;
			animation-duration: 3s;
		}
		@keyframes l15{ 
			100%{transform: rotate(1turn)}
		}
	</style>
</head>
<body>
	<div id="loadHolder" class="fullcon flow centroid">
		<div class="gap-sm flow">
			<span>loading...</span>
			<div class="loader"></div>
		</div>
	</div>

	<script>
		function checkrun() {
			// setTimeout(hideloader,4000);
			const dolater = () => {
				alert_info('done');
			}

			window[fetch_bypass]('./checklogin','post').then(d => {
				responseHandler(d,dolater,d);
			})
		}

		function hideloader() {
			loadHolder.animate([
				{opacity: 1,translate: '0 0'},
				{opacity: 0,translate: '0 -45px'},
			],{
				duration: 700,
				easing: 'ease-out',
				fill: 'forwards',
			})
		}

		callOnLoad.push({act: checkrun});
	</script>
</body>