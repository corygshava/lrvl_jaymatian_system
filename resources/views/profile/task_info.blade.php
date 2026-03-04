<?php
	// require_once __DIR__.'/piece_task_view_styles.php';
?>

@include('pieces.task_view_styles')

<style>
	.profile_card{
		max-width: 100vw;
		/*overflow: hidden;*/
	}
</style>

<div class="container-fluid">
	<div class="page-header">
		<div class="page-title">
			<span class="h4">Manage Task</span>
			<p>Here you can view all information regarding a task. from <b>samples</b>, to <b>payments</b> and to <b>reports</b></p>
		</div>
		<div class="header-actions">
			<button class="mybtn secondary" data-runme="refresh_data">
				<i class="fas fa-sync-alt"></i>
				Refresh
			</button>
		</div>
	</div>

	<div class="profile_card themeround panelbg w3-hide slide-up" id="task_brief">
		<div class="card-header panelbg themegrad flowline overflow left centerline themeround w3-display-container">
			<div>
				<div class="flowline overflow left">
					<strong class="h3" id="task-number" style="display: inline-block;width: auto !important;">--</strong>
					<button class="mybtn trans border" data-copythis="#task-number"><i class="fa fa-copy"></i></button>
				</div>
				<div class="distance-sm">
					<span class="badge status-pending" id="task-status-badge">
						<i class="fas fa-clock"></i> Pending
					</span>
					<span class="badge payment-unpaid ml-2" id="payment-status-badge">
						<i class="fas fa-credit-card"></i> Unpaid
					</span>
				</div>
			</div>
			<div class="w3-display-right spacy-md" style="z-index: 102">
				<div class="dropdown">
					<button class="btn btn-link text-white" type="button" data-toggle="dropdown">
						<i class="fas fa-ellipsis-v"></i>
					</button>
					<div class="dropdown-menu dropdown-menu-right themeround panelbg border-0 w3-card">
						<a class="dropdown-item spacy-tn" href="#" onclick="recordPayment()" id="option_payment_btn">
							<i class="fas fa-money-bill-wave mr-2 text-muted"></i>Record Payment
						</a>
						<a class="dropdown-item spacy-tn" href="#" onclick="addNotes()" id="option_note_btn">
							<i class="fas fa-sticky-note mr-2 text-muted"></i>Add Notes
						</a>
					</div>
				</div>
			</div>
		</div>

		<!-- task brief -->
		<div class="flowline overflow w3-center stats">
			<div class="stat">
				<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Client</small>
				<span class="font-weight-bold" data-copyme="#client-name">--</span>
			</div>
			<div class="stat">
				<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Status</small>
				<span class="font-weight-bold" id="task-status-text">--</span>
			</div>
			<div class="stat">
				<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Payment status</small>
				<span class="font-weight-bold hideicon" data-copyme="#payment-status-badge">--</span>
			</div>
			<div class="stat">
				<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Samples</small>
				<span class="font-weight-bold" id="samples_count">--</span>
			</div>
			<div class="stat">
				<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Payments</small>
				<span class="font-weight-bold" id="payments_count">--</span>
			</div>
			<div class="stat">
				<small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.7rem;">Reports</small>
				<span class="font-weight-bold" id="reports_count">--</span>
			</div>
		</div>
	</div>

	<div id="loaderInterface" class="spacy-md flow centroid gap-sm">
		<span id="load_txt">loading task information</span>
		<div class="loader"></div>
	</div>

	<div id="err_box" class="w3-hide slide-up">
		<div class="spacy-lg w3-center">
			<span class="h3" id="err_hed">Info</span>
			<p id="error_par">loading task record ...</p>
		</div>
	</div>

	<div id="info_stuff" class="w3-hide flow centroid">
		<ul class="nav nav-pills nav-stacked">
			<li class="nav-item">
				<a href="#" class="nav-link active themeround">Stuff</a>
			</li>
			<li class="nav-item">
				<a href="#" class="nav-link">Stuff 2</a>
			</li>
			<li class="nav-item disabled">
				<a href="#" class="nav-link">Last</a>
			</li>
		</ul>
	</div>
</div>

<script>
	window['task_info_data'] = {};

	window['runOnAwake'] = () => {
		setTimeout(() => {
			init_task();
			// alert_info('runing autoload');
			refreshUI(300);
		},400);
	}

	window['runOnLeave'] = () => {
		// alert_danger('leaving the pages');
	}

	// initialisers
	window['init_task'] = () => {
		let t_id = window['cur_task_id'];

		const step1 = () => {
			window[fetch_bypass](linkAtlas['get_task'],{id: t_id},'POST').then((d) => {
				responseHandler(d,step2,d);

				if(d.result){
					showError(false);
				} else {
					showError(true,{heading: 'Error',text: d.message});
				}
			})
		};

		const step2 = (d) => {
			task_info_data.taskData = d.sendme;

			renderTaskBrief();

			load_txt.innerHTML = 'loading task based information';

			alert_info('loading individual stuff');

			window[fetch_bypass](linkAtlas['get_payments'],{taskid: t_id},'POST').then(() => {
				alert_info('loaded payments');
			}).catch(d => {
				hidemainLoader();
				showError(true,{heading: 'Error Getting payments',text: d.message});
			});
		}

		const step3 = (d) => {
			// load payments here
		}

		const step4 = (d) => {
			// load reports here
		}

		const final_step = (d) => {
			alert_dark('rendering stuff');
			pick_step(0);
			showstuffUI();
		}

		load_txt.innerHTML = 'loading task information';
		showmainLoader();
		showError(false);

		setTimeout(() => {
			step1();
		},1200);
	}

	// navops
	window['pick_step'] = (n) => {
		alert_info('loading step: ' + n);
		refreshUI(300);
	}

	// utilities
	window['showError'] = (con = true,d) => {
		let dft = {
			heading: 'Error',
			text: 'something went wrong, reload page'
		};
		d = {...dft,...d};

		let cl = con ? 'remove' : 'add';
		err_box.classList[cl]('w3-hide');
		err_box.querySelector('span').innerHTML = d.heading;
		err_box.querySelector('p').innerHTML = d.text;
	}
	window['showmainLoader'] = () => {
		loaderInterface.classList.remove('w3-hide');
		info_stuff.classList.add('w3-hide');
		task_brief.classList.add('w3-hide');
	}
	window['hidemainLoader'] = () => {
		loaderInterface.classList.add('w3-hide');
	}
	window['showstuffUI'] = () => {
		info_stuff.classList.remove('w3-hide');
	}

	// actions
	window['refresh_data'] = () => {
		alert_info('refreshing information');
		init_task();
	}

	// renderers
	window['render_all_data'] = () => {
		renderTaskBrief();
	}

	window['renderTaskBrief'] = () => {
		alert_info('showing task brief');

		task_brief.classList.remove('w3-hide');
	}
</script>

