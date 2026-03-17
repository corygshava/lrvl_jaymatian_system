<?php
	/*
	require_once __DIR__.'/piece_shared_dash_styles.php';
	require_once __DIR__.'/piece_simple_table.php';
	// */
?>

{{-- <!-- --}}
@include('pieces.dash_styles')
@include('pieces.codes_simpletables')
{{-- --> --}}

<div class="container-fluid">
	<div class="distance-md">
		<span class="text-muted h4">Welcome <b class="themetxt" data-role="user_name_display">User</b></span>
		<p>this is your dashboard, you'll be able to view all relevant information at a glance</p>
	</div>

	<div id="loaderInterface" class="spacy-md flow centroid gap-sm slide-up">
		<span id="load_txt">loading dashboard information</span>
		<div class="loader"></div>
	</div>

	<div id="err_box" class="w3-hide slide-up">
		<div class="spacy-lg w3-center">
			<span class="h3" id="err_hed">Info</span>
			<p id="error_par">loading task record ...</p>
		</div>
	</div>

	<div id="dash_stuff" class="w3-hide">
		<div class="panelbg spacy-sm themeround distance-sm">
			<div>
				<span class="h4">Daily summary</span>
				<p>Here is a brief summary for all relevant information for today</p>
			</div>
			<div class="stats-row">
				<div class="stat-card modern" data-role="dashstat">
					<div class="myicon" data-subrole="stat_amt">
						<i class="fa fa-money-bill"></i>
					</div>
					<div class="stats">
						<h3 data-subrole="stat_amt">53,345</h3>
						<span class="text-muted" data-subrole="stat_cap">Total Sales Today</span>
					</div>
				</div>
			</div>
		</div>
		<div class="panelbg spacy-sm themeround distance-sm">
			<div>
				<span class="h4">Quick Actions</span>
				<p>Here are some quick actions </p>
			</div>
			<div class="row mt-3">
				<div class="col-md-2 col-sm-6 mb-3">
					<div class="action-card card themeround text-center p-3" onclick="createNewTask()">
						<div class="action-icon bg-primary text-white mx-auto">
							<i class="fas fa-folder-plus"></i>
						</div>
						<h6 class="mb-0 font-weight-bold">New Task</h6>
						<small class="text-muted">Create task</small>
					</div>
				</div>
				<div class="col-md-2 col-sm-6 mb-3">
					<div class="action-card card themeround text-center p-3" onclick="viewClients()">
						<div class="action-icon bg-primary text-white mx-auto">
							<i class="fas fa-users"></i>
						</div>
						<h6 class="mb-0 font-weight-bold">Manage Clients</h6>
						<small class="text-muted">View registered clients</small>
					</div>
				</div>
				<div class="col-md-2 col-sm-6 mb-3">
					<div class="action-card card themeround text-center p-3" onclick="viewTasks()">
						<div class="action-icon bg-success text-white mx-auto">
							<i class="fas fa-folder"></i>
						</div>
						<h6 class="mb-0 font-weight-bold">View Tasks</h6>
						<small class="text-muted">All client Tasks</small>
					</div>
				</div>
				<div class="col-md-2 col-sm-6 mb-3">
					<div class="action-card card themeround text-center p-3" onclick="viewSamples()">
						<div class="action-icon bg-warning text-white mx-auto">
							<i class="fas fa-flask"></i>
						</div>
						<h6 class="mb-0 font-weight-bold">View Samples</h6>
						<small class="text-muted">Sample history</small>
					</div>
				</div>
				<div class="col-md-2 col-sm-6 mb-3">
					<div class="action-card card themeround text-center p-3" onclick="viewReports()">
						<div class="action-icon bg-info text-white mx-auto">
							<i class="fas fa-file-export"></i>
						</div>
						<h6 class="mb-0 font-weight-bold">View Reports</h6>
						<small class="text-muted">Client reports</small>
					</div>
				</div>
				<div class="col-md-2 col-sm-6 mb-3">
					<div class="action-card card themeround text-center p-3" onclick="viewPayments()">
						<div class="action-icon bg-info text-white mx-auto">
							<i class="fas fa-money-bill"></i>
						</div>
						<h6 class="mb-0 font-weight-bold">View Payments</h6>
						<small class="text-muted">All payments made</small>
					</div>
				</div>
			</div>
		</div>

		<div class="panelbg spacy-sm themeround distance-sm w3-display-container">
			<div class="w3-display-topright spacy-sm">
				<button class="mybtn primary sm" data-runme="viewTasks">view all tasks</button>
			</div>
			<div>
				<span class="h4">Pending tasks</span>
				<p>Here are some tasks that are not yet complete </p>
			</div>
			<div>
				<i>pending tasks table</i>
			</div>
		</div>

		<div class="panelbg spacy-sm themeround distance-sm w3-display-container">
			<div class="w3-display-topright spacy-sm">
				<button class="mybtn primary sm" data-runme="viewSamples">view all samples</button>
			</div>
			<div>
				<span class="h4">pending samples</span>
				<p>Here are some samples that are not yet processed</p>
			</div>
			<div>
				<i>pending samples table</i>
			</div>
		</div>
	</div>
</div>

<script>
	let dash_runtime_data = {};

	/*
	dash_runtime_data.statsData = [
		{
			icon: 'fa-flask',
			caption: 'sales',
			amt: 54300,
		},
		{
			icon: 'fa-flask',
			caption: 'tasks',
			amt_progress: 3,
			amt_total: 54300,
		},
		...
	]
	// */

	window['runOnAwake'] = () => {
		delete(window['refreshData']);
		updateSessionUI();

		setTimeout(() => {
			initdashsetup();
			// alert_info('runing autoload');
		},400);
	}

	window['initdashsetup'] = () => {
		const step1 = () => {
			window[fetch_bypass](linkAtlas['get_staff_stats'],{},'POST').then((d) => {
				responseHandler(d,step2,d);

				if(d.result){
					showError(false);
				} else {
					showError(true,{heading: 'Error getting stats',text: d.message});
				}
			}).catch(err => {
				showError(true,{heading: 'Error getting stats',text: err.message});
			})
		};

		const step2 = (d) => {
			dash_runtime_data.staffStats = d.sendme;

			// get incomplete tasks
			window[fetch_bypass](linkAtlas['get_incomplete_tasks'],{},'POST').then((d) => {
				responseHandler(d,step3,d);
			}).catch(err => {
				showError(true,{heading: 'Error getting tasks',text: err.message});
			});
		}

		const step3 = (d) => {
			dash_runtime_data.taskData = d.sendme;

			// get unprocessed samples
			window[fetch_bypass](linkAtlas['get_incomplete_samples'],{},'POST').then((d) => {
				responseHandler(d,step4,d);
			}).catch(err => {
				showError(true,{heading: 'Error getting samples',text: err.message});
			});
		}

		const step4 = (d) => {
			dash_runtime_data.samplesData = d.sendme;

			final_step();
		}

		const final_step = () => {
			alert_info('done fetching');
		}

		load_txt.innerHTML = 'loading dashboard information';
		showmainLoader();
		showError(false);

		setTimeout(() => {
			step1();
		},1200);
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
		loaderInterface.classList[con ? 'add' : 'remove']('w3-hide');
	}
	window['showmainLoader'] = () => {
		loaderInterface.classList.remove('w3-hide');
		dash_stuff.classList.add('w3-hide');
	}
	window['hidemainLoader'] = () => {
		loaderInterface.classList.add('w3-hide');
	}

	window['refresh_data'] = () => {
		alert_info('refreshing information');
		initdashsetup();
	}
	window['loadthispage'] = (p) => {
		loadPage({
			template: linkAtlas[p],
			sidenote: pageNames[p],
		});
	}

	// actions
	window['createNewTask'] = () => {
		loadthispage('add_task');
	}
	window['viewClients'] = () => {
		loadthispage('view_clients');
	}
	window['viewTasks'] = () => {
		loadthispage('view_tasks');
	}
	window['viewSamples'] = () => {
		loadthispage('view_samples');
	}
	window['viewReports'] = () => {
		loadthispage('view_reports');
	}
	window['viewPayments'] = () => {
		loadthispage('view_payments');
	}

	// summary tables
	window['renderTasks'] = () => {
		const data = {
			data: dash_runtime_data.taskData,
			cols: [
				{
					field: 'id',
					cap: '',
					show: (v,row,n) => {
						return n + 1;
					}
				},
				{
					field: 'sample_no',
					cap: 'sample Number',
				},
				{
					field: 'sample_type',
					cap: 'sample type',
					show: (v,r) => {
						return `
							<span class="sample-type-badge type-${v}">${v}</span>
						`;
					}
				},
				{
					field: 'sample_count',
					cap: 'processed',
					show: (v,row) => {
						return `<b>${row['results_count']} / ${row['sample_count']}</b>`;
					}
				},
				{
					field: 'status',
					cap: 'Status',
					show: (v) => {
						return `<b>${sample_state_atlas[v]}</b>`;
					}
				},
			],
			tablename: 'samples_table',
			caption: 'samples',
			viewcall: 'view_rec',
			rowprops: 'data-rectype="sample"',
		};
		render_simpleTable(data);
	}
</script>

