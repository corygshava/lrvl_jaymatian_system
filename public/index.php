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
	<script src="_assets/_vendor/BS4/js/bootstrap.bundle.min.js"></script>
	<script src="_assets/js/chart.js"></script>
	<script src="_assets/js/SuperScript.js"></script>
	<script src="_assets/js/toappend.js"></script>

	<script src="_assets/js/coryG_UIOps.js"></script>
	<script src="_assets/js/customalerter.js"></script>
	<script src="_assets/js/app.js"></script>

	<style>
		/* loader overlay */
			#loadHolder{
				position: fixed;
				top: 0;
				left: 0;
				z-index: 99;
				background: var(--clr-bg);
				pointer-events: none;
			}
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

	<div id="app_ui" class="fullcon"></div>

	<script src="_assets/js/anims.js"></script>

	<script>
		const dft_holder = app_ui;

		async function loadUI({p='/',mt='get',holder=undefined,prerun = () => {alert_info('loading...')},postrun=() => {alert_info('loaded')}}) {
			if(holder === undefined){
				holder = dft_holder;
			}

			if(typeof prerun == "function"){
				prerun();
			}

			showloader();

			window[fetch_ui](p,{},mt).then(d => {
                hideloader();
                holder.innerHTML = d;
                refreshUI(300)

                if(typeof postrun == "function"){
					postrun();
				}
			})
		}

		function checkrun() {
			// setTimeout(hideloader,4000);
			const dolater = () => {
				alert_info('done');
			}

			loadUI({p: './dashboard',mt: "get",postrun: dolater}).then(() => {
				alert_info('dashboard loaded');
			});
		}

		function hideloader() {
			loadHolder.animate([...slidein].reverse(),{...timing});
		}

		function showloader() {
			loadHolder.animate([...slidein],{...timing});
		}

		callOnLoad.push({act: checkrun});
	</script>
</body>
