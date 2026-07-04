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
			<button class="mybtn primary" data-runme="render_required_data"><i data-myicon="cash_reports"></i> Daily report</button>
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
		<div class="panelbg spacy-sm themeround distance-sm slide-up">
			<div>
				<span class="h4">Daily summary</span>
				<p>Here is a brief summary for all relevant information for today</p>
			</div>
			<div class="stats-row" id="statsholder">
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

		<div class="panelbg spacy-sm themeround distance-sm slide-up" style="animation-delay: 200ms;opacity: 0;animation-fill-mode: forwards;">
			<div>
				<span class="h4">Quick Actions</span>
				<p>Here are some quick actions </p>
			</div>
			<div class="row mt-3">
				<div class="col-md-2 col-sm-6 mb-3">
					<div class="action-card card themeround text-center p-3" onclick="viewClients()">
						<div class="action-icon bg-primary text-white mx-auto">
							<i data-myicon="clients"></i>
						</div>
						<h6 class="mb-0 font-weight-bold">Manage Clients</h6>
						<small class="text-muted">View registered clients</small>
					</div>
				</div>
				<div class="col-md-2 col-sm-6 mb-3">
					<div class="action-card card themeround text-center p-3" onclick="viewTasks()">
						<div class="action-icon bg-success text-white mx-auto">
							<i data-myicon="tasks"></i>
						</div>
						<h6 class="mb-0 font-weight-bold">View Tasks</h6>
						<small class="text-muted">All client Tasks</small>
					</div>
				</div>
				<div class="col-md-2 col-sm-6 mb-3">
					<div class="action-card card themeround text-center p-3" onclick="viewSamples()">
						<div class="action-icon bg-warning text-white mx-auto">
							<i data-myicon="samples"></i>
						</div>
						<h6 class="mb-0 font-weight-bold">View Samples</h6>
						<small class="text-muted">Sample history</small>
					</div>
				</div>
				<div class="col-md-2 col-sm-6 mb-3">
					<div class="action-card card themeround text-center p-3" onclick="viewReports()">
						<div class="action-icon bg-info text-white mx-auto">
							<i data-myicon="reports"></i>
						</div>
						<h6 class="mb-0 font-weight-bold">View Reports</h6>
						<small class="text-muted">Client reports</small>
					</div>
				</div>
				<div class="col-md-2 col-sm-6 mb-3">
					<div class="action-card card themeround text-center p-3" onclick="viewPayments()">
						<div class="action-icon bg-info text-white mx-auto">
							<i data-myicon="payments"></i>
						</div>
						<h6 class="mb-0 font-weight-bold">View Payments</h6>
						<small class="text-muted">All payments made</small>
					</div>
				</div>
				<div class="col-md-2 col-sm-6 mb-3">
					<div class="action-card card themeround text-center p-3" onclick="createNewTask()">
						<div class="action-icon bg-primary text-white mx-auto">
							<i data-myicon="add_task"></i>
						</div>
						<h6 class="mb-0 font-weight-bold">New Task</h6>
						<small class="text-muted">Create task</small>
					</div>
				</div>
			</div>
			<div class="row mt-3">
				<div class="col-md-2 col-sm-6 mb-3">
					<div class="action-card card themeround text-center p-3" onclick="viewthis('invoices_mgt')">
						<div class="action-icon bg-primary text-white mx-auto">
							<i data-myicon="invoices"></i>
						</div>
						<h6 class="mb-0 font-weight-bold">View Invoices</h6>
						<small class="text-muted">List all invoices</small>
					</div>
				</div>
				<div class="col-md-2 col-sm-6 mb-3">
					<div class="action-card card themeround text-center p-3" onclick="viewthis('expenses_mgt')">
						<div class="action-icon bg-primary text-white mx-auto">
							<i data-myicon="expenses"></i>
						</div>
						<h6 class="mb-0 font-weight-bold">View Expenses</h6>
						<small class="text-muted">List all Expenses</small>
					</div>
				</div>
				<div class="col-md-2 col-sm-6 mb-3 w3-hide">
					<div class="action-card card themeround text-center p-3" onclick="viewthis('daily_reports')">
						<div class="action-icon bg-primary text-white mx-auto">
							<i data-myicon="cash_reports"></i>
						</div>
						<h6 class="mb-0 font-weight-bold">Daily report</h6>
						<small class="text-muted">View daily reports</small>
					</div>
				</div>
			</div>
		</div>

		<div class="panelbg spacy-sm themeround distance-sm w3-display-container slide-up" style="animation-delay: 400ms;opacity: 0;animation-fill-mode: forwards;">
			<div class="w3-display-topright spacy-sm">
				<button class="mybtn primary sm" data-runme="viewTasks">view all tasks</button>
			</div>
			<div>
				<span class="h4">Pending tasks</span>
				<p>Here are some tasks that are not yet complete, <b>click</b> on one to start managing it</p>
			</div>
			<div>
				<div class="table-responsive themeround border">
					<table class="table table-hover mb-0 table-modern" data-role="tasks_table">
						<thead data-subrole="tableHead" class="thead-light">
						</thead>
						<tbody data-subrole="tableBody">
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="panelbg spacy-sm themeround distance-sm w3-display-container slide-up w3-hide" style="animation-delay: 200ms;opacity: 0;animation-fill-mode: forwards;">
			<div class="w3-display-topright spacy-sm">
				<button class="mybtn primary sm" data-runme="viewSamples">view all samples</button>
			</div>
			<div>
				<span class="h4">pending samples</span>
				<p>Here are some samples that are not yet processed</p>
			</div>
			<div>
				<div class="table-responsive themeround border">
					<table class="table table-hover mb-0 table-modern" data-role="samples_table">
						<thead data-subrole="tableHead" class="thead-light">
						</thead>
						<tbody data-subrole="tableBody">
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	let dash_runtime_data = {};
	window['is_staff_page'] = true;
	window['killOnLeave'] = [
		'is_staff_page',
		'runOnAwake',
		'initdashsetup',
		'render_all_stuff',
		'showError',
		'showmainLoader',
		'hidemainLoader',
		'refresh_data',
		'loadthispage',
		'createNewTask',
		'viewClients',
		'viewTasks',
		'viewSamples',
		'viewReports',
		'viewPayments',
		'viewthis',
		'refreshData',
		'view_sample',
		'view_task_0',
		'view_task',
		'renderStats',
		'renderTasks',
		'renderSamples',
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

	window['runOnAwake'] = () => {
		updateSessionUI();

		setTimeout(() => {
			initdashsetup();
			// alert_info('runing autoload');
		},400);
	}

	window['initdashsetup'] = () => {
		const step0 = () => {
			// return;
			window[fetch_bypass](linkAtlas['get_daily_report'],{},'POST').then(dta => {
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
			window['daily_report_data'].daily_report_loadtime = new Date();

			window[fetch_bypass](linkAtlas['get_staff_stats'],{},'POST').then((d) => {
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

			let staffStats = [
				{
					"caption": "Clients Registered",
					"clickop" : 'viewthis("view_clients")',
					"icon": iconAtlas['samples'],
					"amt": thereport.clients_count
				},
				{
					"caption": "Samples Entered",
					"clickop" : 'viewthis("view_samples")',
					"icon": iconAtlas['samples'],
					"amt": thereport.samples_count,
				},
				{
					"caption": "Tasks Created",
					"clickop" : 'viewthis("view_tasks")',
					"icon": iconAtlas['tasks'],
					"amt": thereport.tasks_count,
				},
				{
					"caption": "reports Generated",
					"clickop" : 'viewthis("view_reports")',
					"icon": iconAtlas['reports'],
					"amt": thereport.reports_count,
				},
				{
					"caption": "Invoices Generated",
					"clickop" : 'viewthis("view_invoices")',
					"icon": iconAtlas['invoices'],
					"amt": thereport.invoices_count,
				},
				{
					"caption": "Expense Records Generated",
					"clickop" : 'viewthis("view_expenses")',
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

			// return;
			step2({
				staff: staffStats,
				totals: totalStats,
				finances: finStats,
			});
		}

		const step2 = (dta) => {
			console.log('all stats: ',dta);
			dash_runtime_data.staffStats = dta.staff;
			dash_runtime_data.totalStats = dta.totals;

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
		renderTasks();
		renderSamples();

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
		window['viewthis'] = (wh) => {
			loadPage({template: linkAtlas[wh],sidenote: pageNames[wh]});
		}
		window['refreshData'] = () => {
			initdashsetup();
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
		window['renderStats'] = () => {
			// alert_info('render this stuff');

			let t_stats = dash_runtime_data.totalStats;
			let a_stats = dash_runtime_data.staffStats;
			// let f_stats = dash_runtime_data.finStats;

			let stattypes = [
				t_stats,
				a_stats,
				// f_stats,
			];
			let outht = '';

			statsholder.innerHTML = outht;

			let sections = [];
			let s_ht = '';

			stattypes.forEach(st => {
				let stats = st;
				s_ht = '';

				stats.forEach((el, id) => {
					let prf = el.prefix == undefined ? '' : el.prefix + ' ';
					let amt_dis = el.amt_progress == undefined ? `
						${prf}${formatNumber(el.amt,2)}
					` : `
						${prf}${el.amt_progress} / ${el.amt_total}
					`;
					let clickact = el.clickop == undefined ? '' : `onclick='${el.clickop}'`;
					s_ht += `
						<div class="stat-card modern" data-role="dashstat" ${clickact}>
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
					<b>Main summary <span class="themetxt">[today]</span></b>
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
		window['renderSamples'] = () => {
		const data = {
			data: dash_runtime_data.samplesData.data,
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
							<span class="sample-type-badge type-${v.toLowerCase()}">${v}</span>
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

