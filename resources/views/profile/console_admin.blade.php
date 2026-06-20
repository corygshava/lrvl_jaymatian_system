<?php
	/*
	require_once __DIR__.'/piece_shared_dash_styles.php';
	require_once __DIR__.'/piece_simple_table.php';
	require_once __DIR__.'/piece_samples_extras.php';
	require_once __DIR__.'/piece_cash_report_xtras.php';
	// */
?>

{{-- <!-- --}}
@include('pieces.dash_styles')
@include('pieces.codes_simpletables')
@include('pieces.samples_xtras')
@include('pieces.cashreport_xtras')
{{-- --> --}}

<style>
	/*[data-runme="save_daily_report"]{display: none;}*/
	.btn-outline-themer{
		border: 1px solid var(--themecolor);
	}
	.btn-outline-themer:hover{
		background: var(--darkcolor);
		border: 1px solid var(--darkcolor);
		color: #fff;
	}
	.btn-outline-themer.active{
		background: var(--themecolor);
		color: #fff;
	}
</style>

<div class="container-fluid">
	<div class="page-header slide-r">
		<div class="page-title">
			<span class="text-muted h4">Welcome <b class="themetxt" data-role="user_name_display">User</b></span>
			<p>this is your dashboard, you'll be able to view all relevant information at a glance</p>
		</div>
		<div class="header-actions">
			<button class="mybtn secondary" data-runme="refreshData">
				<i class="fas fa-sync-alt"></i>
				Refresh
			</button>
		</div>
	</div>

	<div>
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
	</div>

	<div id="dash_stuff" class="w3-hide">
		<div class="panelbg spacy-sm themeround distance-tn">
			<div class="flow left gap-sm w3-display-container">
				<span class="h4" data-role="stats-heading">Activity scope</span>

				<div class="flowline spread in_fullwidth centerline overflow">
					<div class="spacy-tn border themeround">
						<div class="flowline left gap-tn overflow except scopebtns">
							<button class="btn btn-outline-primary active" data-runme="reset_timerange" data-myid="0">daily</button>
							<button class="btn btn-outline-primary" data-runme="reset_timerange" data-myid="1">weekly</button>
							<button class="btn btn-outline-primary" data-runme="reset_timerange" data-myid="2">monthly</button>
						</div>
					</div>

					<div class="w3-display-topright_ spacy-sm">
						<button class="mybtn primary" data-runme="render_required_data"><i data-myicon="cash_reports"></i> view System Report</button>
					</div>
				</div>
			</div>
		</div>

		<!-- stats guy -->
		<div class="panelbg spacy-sm themeround distance-sm slide-up">
			<div>
				<span class="h4" data-role="stats-heading">System Summary</span>
				<p>Here is a brief summary for all relevant information for today</p>
			</div>
			<div id="statsholder">
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

		<div class="panelbg spacy-sm themeround distance-sm slide-up w3-hide" style="animation-delay: 200ms;opacity: 0;animation-fill-mode: forwards;">
			<div>
				<span class="h4">Quick Actions</span>
				<p>Here are some quick actions </p>
			</div>
			<div class="row mt-3">
				<div class="col-md-2 col-sm-6 mb-3">
					<div class="action-card card themeround text-center p-3" onclick="viewUsers()">
						<div class="action-icon bg-success text-white mx-auto">
							<i class="fas fa-folder"></i>
						</div>
						<h6 class="mb-0 font-weight-bold">View User Roles</h6>
						<small class="text-muted">All user roles and their permissions</small>
					</div>
				</div>
				<div class="col-md-2 col-sm-6 mb-3">
					<div class="action-card card themeround text-center p-3" onclick="viewUsers()">
						<div class="action-icon bg-success text-white mx-auto">
							<i class="fas fa-folder"></i>
						</div>
						<h6 class="mb-0 font-weight-bold">View Users</h6>
						<small class="text-muted">All user records</small>
					</div>
				</div>
				<div class="col-md-2 col-sm-6 mb-3">
					<div class="action-card card themeround text-center p-3" onclick="viewClients()">
						<div class="action-icon bg-primary text-white mx-auto">
							<i class="fas fa-users"></i>
						</div>
						<h6 class="mb-0 font-weight-bold">view Clients</h6>
						<small class="text-muted">View registered clients</small>
					</div>
				</div>
				<div class="col-md-2 col-sm-6 mb-3">
					<div class="action-card card themeround text-center p-3" onclick="viewTasks()">
						<div class="action-icon bg-success text-white mx-auto">
							<i class="fas fa-folder"></i>
						</div>
						<h6 class="mb-0 font-weight-bold">View Tasks</h6>
						<small class="text-muted">All task records</small>
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
					<div class="action-card card themeround text-center p-3" onclick="viewPayments()">
						<div class="action-icon bg-info text-white mx-auto">
							<i class="fas fa-money-bill"></i>
						</div>
						<h6 class="mb-0 font-weight-bold">View Payments</h6>
						<small class="text-muted">All payments made</small>
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
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="panelbg spacy-sm themeround distance-sm slide-up">
					<div>
						<span class="h4">Sales Chart</span>
					</div>
					<div class="chart-container">
						<canvas id="salesLineChart"></canvas>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="panelbg spacy-sm themeround distance-sm slide-up">
					<div>
						<span class="h4">samples Chart</span>
					</div>
					<div class="chart-container">
						<canvas id="samplesLineChart"></canvas>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="panelbg spacy-sm themeround distance-sm slide-up">
					<div>
						<span class="h4">visits Chart</span>
					</div>
					<div class="chart-container">
						<canvas id="visitsLineChart"></canvas>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="panelbg spacy-sm themeround distance-sm slide-up">
					<div>
						<span class="h4">income Chart</span>
					</div>
					<div class="chart-container">
						<canvas id="incomeLineChart"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	let charts = {};
	window['is_admin_page'] = true;
	window['dash_runtime_data'] = {
		charts: {}
	};

	const timerange_atlas = timeranges;
	window['timerange'] = timerange_atlas[0];

	window['killOnLeave'] = [
		'dash_runtime_data',
		'timerange',
		'is_admin_page',
	];

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

	// initialiser
		window['runOnAwake'] = () => {
			initUIs();
			updateSessionUI();

			setTimeout(() => {
				initdashsetup();
				// alert_info('runing autoload');
			},400);
		}
		window['initUIs'] = () => {
			let bx = document.querySelector('.scopebtns');
			let outht = ``;

			timerange_atlas.forEach((t,n) => {
				outht += `
					<button class="btn btn-outline-themer ${n != 0 ? "" : " active"}" data-runme="reset_timerange" data-myid="${n}">
						${t.split('_').join(" ")}
					</button>
				`;
			})

			outht += `
				<button class="btn btn-outline-themer" data-runme="change_date">
					<i class="fa fa-calendar"></i>
				</button>
				<button class="btn btn-outline-themer w3-hide" data-runme="change_date_range">
					<i class="fa fa-calendar-alt"></i>
				</button>
			`;

			bx.innerHTML = outht;
		}
		window['initdashsetup'] = () => {
			const step0 = () => {
				window[fetch_bypass](linkAtlas['get_daily_report'],{t_range: timerange},'POST').then(dta => {
					if(!dta.success || !dta.result){
						throw new Error(dta.message);
					}

					responseHandler(dta, step1, dta);
				}).catch(err => {
					showError(true,{heading: 'Error getting daily report',text: err.message});
				})
			}
			const step1 = (dta) => {
				let thereport = dta.sendme;
				window['daily_report_data'].daily_report = thereport;
				window['daily_report_data'].daily_report.context = timerange;
				window['daily_report_data'].daily_report_loadtime = new Date();

				window[fetch_bypass](linkAtlas['get_admin_stats'],{t_range: timerange},'POST').then((d) => {

					if(d.result){
						showError(false);
					} else {
						showError(true,{heading: 'Error getting stats',text: d.message});
						return;
					}
					responseHandler(d,step1b,d);
				}).catch(err => {
					showError(true,{heading: 'Error getting stats',text: err.message});
				})
			};

			const step1b = (dta) => {
				let fetched = dta.sendme;
				let thereport = window['daily_report_data'].daily_report;

				let totalStats = [
					{
						"caption": "Incomplete Tasks",
						"icon": iconAtlas['tasks'],
						"amt": fetched.incomplete_tasks_total,
					},
					{
						"caption": "Unpaid tasks",
						"icon": iconAtlas['tasks'],
						"amt": fetched.unpaid_tasks_total,
					},
					{
						"caption": "Incomplete Samples",
						"icon": iconAtlas['samples'],
						"amt": fetched.incomplete_samples_total,
					},
					{
						"caption": "Unpaid Invoices",
						"icon": iconAtlas['invoices'],
						"amt": fetched.unpaid_invoices_total,
					},
				]

				let adminStats = [
					{
						"caption": "Clients Registered",
						"icon": iconAtlas['samples'],
						"amt": thereport.clients_count
					},
					{
						"caption": "Samples Entered",
						"icon": iconAtlas['samples'],
						"amt": thereport.samples_count,
					},
					{
						"caption": "Tasks Created",
						"icon": iconAtlas['tasks'],
						"amt": thereport.tasks_count,
					},
					{
						"caption": "reports Generated",
						"icon": iconAtlas['reports'],
						"amt": thereport.reports_count,
					},
					{
						"caption": "Invoices Generated",
						"icon": iconAtlas['invoices'],
						"amt": thereport.invoices_count,
					},
					{
						"caption": "Expense Records Generated",
						"icon": iconAtlas['expenses'],
						"amt": thereport.expenses_count,
					}
				];

				let finStats = [
					{
						"caption": "Carried Over",
						"icon": iconAtlas['cash_reports'],
						"prefix": 'KES',
						"amt": formatNumber(fetched.today.carry_over,2),
					},
					{
						"caption": "Total income (today)",
						"icon": iconAtlas['payments'],
						"prefix": 'KES',
						"amt": formatNumber(fetched.today.total_daily_cash,2),
					},
					{
						"caption": "Gross Cash",
						"icon": iconAtlas['payments'],
						"prefix": 'KES',
						"amt": formatNumber(fetched.today.total_cash,2),
					},
					{
						"caption": "Total expenses",
						"icon": iconAtlas['expenses'],
						"prefix": 'KES',
						"amt": formatNumber(fetched.today.expenditure,2),
					},
					{
						"caption": "Total Cash Available",
						"icon": iconAtlas['expenses'],
						"prefix": 'KES',
						"amt": formatNumber(fetched.cash_available,2),
					},
				]
				step2({
					admin: adminStats,
					totals: totalStats,
					finances: finStats,
				});
			}

			const step2 = (dta) => {
				console.log('all stats: ',dta);
				dash_runtime_data.adminStats = dta.admin;
				dash_runtime_data.totalStats = dta.totals;
				dash_runtime_data.finStats = dta.finances;

				alert_dark('now getting sales chart');

				// get incomplete tasks
				window[fetch_bypass](linkAtlas['get_sales_chart_data'],{t_range: timerange},'POST').then((d) => {
					responseHandler(d,step3,d);
				}).catch(err => {
					showError(true,{heading: 'Error getting sales chart data',text: err.message});
				});
			}

			const step3 = (d) => {
				dash_runtime_data.charts = {};
				dash_runtime_data.charts.salesdata = d.sendme;

				// get samples
				window[fetch_bypass](linkAtlas['get_samples_chart_data'],{t_range: timerange},'POST').then((d) => {
					responseHandler(d,step4,d);
				}).catch(err => {
					showError(true,{heading: 'Error getting samples',text: err.message});
				});
			}

			const step4 = (d) => {
				dash_runtime_data.charts.samplesdata = d.sendme;

				// get unprocessed samples
				window[fetch_bypass](linkAtlas['get_visits_chart_data'],{t_range: timerange},'POST').then((d) => {
					responseHandler(d,step5,d);
				}).catch(err => {
					showError(true,{heading: 'Error getting samples',text: err.message});
				});
			}

			const step5 = (d) => {
				dash_runtime_data.charts.visitsdata = d.sendme;

				// get unprocessed samples
				window[fetch_bypass](linkAtlas['get_income_chart_data'],{t_range: timerange},'POST').then((d) => {
					responseHandler(d,step6,d);
				}).catch(err => {
					showError(true,{heading: 'Error getting samples',text: err.message});
				});
			}

			const step6 = (d) => {
				dash_runtime_data.charts.incomedata = d.sendme;
				final_step();
			}

			const final_step = () => {
				alert_dark('done fetching');
				render_all_stuff();
			}

			load_txt.innerHTML = 'loading dashboard information';
			showmainLoader();
			showError(false);

			setTimeout(() => {
				step0();
			},1200);
		}
		window['render_all_stuff'] = () => {
			renderStats();

			render_sales_chart();
			render_samples_chart();
			render_visits_chart();
			render_income_chart();

			showmainLoader(false);
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
			loaderInterface.classList[con ? 'add' : 'remove']('w3-hide');
		}
		window['showmainLoader'] = (con = true) => {
			let cls = con ? 'remove' : 'add';
			let altcls = !con ? 'remove' : 'add';

			loaderInterface.classList[cls]('w3-hide');
			dash_stuff.classList[altcls]('w3-hide');
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
		window['refreshData'] = () => {
			initdashsetup();
		}
		window['reset_timerange'] = (el,use_as_is = false) => {
			if(el == undefined){
				alert_danger('invalid procedure call');
				return;
			}

			if(!use_as_is){
				let id = el.dataset.myid == undefined ? 0 : Number(el.dataset.myid);

				if(isNaN(id)){
					alert_danger('invalid timerange pass');
					return;
				}
				if(id >= timerange_atlas.length){
					alert_danger('invalid timerange');
				}

				timerange = timerange_atlas[id];
			} else {
				timerange = el.dataset.myid;
			}

			refreshData();

			let btns = document.querySelectorAll('.scopebtns>.btn');
			btns.forEach(l => {l.classList.remove('active');})
			el.classList.add('active');
		}
		window['change_date'] = (el) => {
			//
			alert_info('changing date');

			let _input = {
				label: 'pick a date',
				field: 'chosen_date',
				typ: 'date',
				// placeholder: 'pick the date to '
				required: true,
			};
			get_input('select date',[_input], send_to_sys);

			function send_to_sys(b) {
				console.log(b);
				alert_dark('gotten input');

				let date_txt = b.chosen_date;
				let date = new Date(date_txt);
				let taday = new Date();

				if(date.getTime() > taday.getTime()){
					alert_danger('sorry, you cant pick a date in the future');
					return;
				}

				el.dataset.myid = b.chosen_date;

				reset_timerange(el,true);
			}
		}
		window['change_date_range'] = (el) => {
			//
			alert_info('changing date range');
			get_input('selected date range',[{},{}], send_to_sys);

			function send_to_sys(b) {
				console.log(b);
				alert_dark(`gotten input: ${JSON.stringify(b)}`);
			}
		}

		// samples ops
		window['view_sample'] = (el,id,w) => {
			let who = {
				rectype: w,
			}

			let rec = dash_runtime_data.samplesData.data[id];
			who.data = rec;

			view_samples_modal_trig.click();
			renderSampleData(who);
		}
		window['view_task_0'] = (el,id,w) => {
			let who = {
				rectype: w,
			}
			let rec = dash_runtime_data.taskData.data[id];
			who.data = rec;
			window['cur_record'] = who;

			loadPage({
				template: linkAtlas['view_task'],
				sidenote: pageNames['view_task'],
			});
		}
		window['view_task'] = (el,id,w) => {
			let rec = dash_runtime_data.taskData.data[id];
			let theid = rec.id;

			window[variableAtlas['task_id']] = theid;

			loadPage({
				template: linkAtlas['task_info'],
				sidenote: pageNames['task_info'],
			});
		}

	// renderers
		window['render_sales_chart'] = () => {
			let cid = 'salesLineChart';
			let data = dash_runtime_data.charts.salesdata;
			let title = 'Total Sales';

			render_line_chart(cid,data,title);
		};
		window['render_samples_chart'] = () => {
			let cid = 'samplesLineChart';
			let data = dash_runtime_data.charts.samplesdata;
			let title = 'Total samples delivered';

			render_line_chart(cid,data,title);
		};
		window['render_visits_chart'] = () => {
			let cid = 'visitsLineChart';
			let data = dash_runtime_data.charts.visitsdata;
			let title = 'Total client visits';

			render_line_chart(cid,data,title);
		};
		window['render_income_chart'] = () => {
			let cid = 'incomeLineChart';
			let data = dash_runtime_data.charts.incomedata;
			let title = 'Total income';

			render_line_chart(cid,data,title);
		};

		window['render_line_chart'] = (cid,chartdata = {},title='Sample Line Chart') => {
			if(cid == undefined){
				alert_danger('invalid chart call');
				return;
			}

			// Get the canvas element
			let thecanvas = document.getElementById(cid);
			if(thecanvas == undefined){
				alert_danger(`canvas \`${cid}\` not found`);
				return;
			}

			const ctx = thecanvas.getContext('2d');
			const dft = {
				labels: ['January', 'February', 'March', 'April', 'May', 'June'],
				datasets: [{
					label: 'Sales',
					data: [12, 19, 3, 5, 2, 3],
					borderColor: 'rgb(75, 192, 192)',
					backgroundColor: 'rgba(75, 192, 192, 0.2)',
					tension: 0.1
				}]
			};

			chartdata = {...dft,...chartdata};

			if(charts[cid]){
				charts[cid].destroy();
			}

			charts[cid] = new Chart(ctx, {
				type: 'line',
				data: chartdata,
				options: {
					responsive: true,
					plugins: {
						legend: {
							position: 'top',
						},
						title: {
							display: true,
							text: title
						}
					},
					scales: {
						y: {
							beginAtZero: true
						}
					}
				}
			});
		}
		window['renderStats'] = () => {
			// alert_info('render this stuff');

			let t_stats = dash_runtime_data.totalStats;
			let a_stats = dash_runtime_data.adminStats;
			let f_stats = dash_runtime_data.finStats;

			let stattypes = [
				t_stats,
				a_stats,
				f_stats,
			];
			let outht = '';

			statsholder.innerHTML = outht;

			let sections = [];
			let s_ht = '';

			stattypes.forEach(st => {
				let stats = st;
				s_ht = '';

				stats.forEach((el,id) => {
					let prf = el.prefix == undefined ? '' : el.prefix + ' ';
					let amt_dis = el.amt_progress == undefined ? `
						${prf}${formatNumber(el.amt,2)}
					` : `
						${prf}${el.amt_progress} / ${el.amt_total}
					`;
					s_ht += `
						<div class="stat-card modern" data-role="dashstat">
							<div class="myicon" data-subrole="stat_amt">
								<i class="fa ${el.icon}"></i>
							</div>
							<div class="stats">
								<h3 data-subrole="stat_amt">${prf} ${el.amt}</h3>
								<span class="text-muted" data-subrole="stat_cap">${el.caption}</span>
							</div>
						</div>
					`;
				});

				sections.push(s_ht);
			});

			outht = `
				<div class="distance-sm">
					<b>Main summary <span class="themetxt">[${timerange}]</span></b>
				</div>
				<div class="stats-row">
					${sections[1]}
				</div>
				<div class="distance-sm">
					<b>System Aggregates</b>
				</div>
				<div class="stats-row">
					${sections[0]}
				</div>
				<div class="distance-sm">
					<b>finances</b>
				</div>
				<div class="stats-row">
					${sections[2]}
				</div>
			`;

			statsholder.innerHTML = outht;
		}
		window['renderTasks'] = () => {
			const data = {
				data: dash_runtime_data.taskData.data,
				cols: [
					{
						cap: 'ID',
						field: 'id',
					},
					{
						cap: 'Task number',
						field: 'task_no',
						sortable: true,
						show: function(value) {
							return `<strong>${value}</strong>`;
						}
					},
					{
						cap: 'Client name',
						field: 'client_name',
						sortable: true,
						show: function(value) {
							return `<strong>${value}</strong>`;
						}
					},
					{
						cap: 'Date added',
						field: 'created_at',
						sortable: true,
						show: function(value) {
							return formatDate0(value);
							// return `<span class="badge ${value}">${value}</span>`;
						}
					},
					{
						cap: 'payment status',
						field: 'payment_status',
						sortable: true,
						show: function(value) {
							let cls = value == '2' ? "w3-text-green" : "w3-black_";
							// let con = value == 'monthly' ? "yes" : "no";
							return `<strong class="${cls}">${payment_state_atlas[value]}</strong>`;
						}
					},
					{
						cap: 'Status',
						field: 'status',
						sortable: true,
						show: function(value) {
							let cls = value == '2' ? "w3-green" : "w3-blue";
							// let con = value == 'monthly' ? "yes" : "no";
							return `<strong class="w3-tag themeround ${cls}">${state_atlas[value]}</strong>`;
						}
					},
					{
						cap: 'is paid',
						field: 'is_paid',
						sortable: true,
						show: function(value) {
							let cls = value == true ? "w3-green" : "w3-black";
							let con = value == true ? "yes" : "no";
							return `<strong class="w3-tag themeround ${cls}">${con}</strong>`;
						}
					}
				],
				tablename: 'tasks_table',
				caption: 'tasks',
				viewcall: 'view_rec',
				rowprops: 'data-rectype="task"',
			};
			render_simpleTable(data);
		}
</script>
