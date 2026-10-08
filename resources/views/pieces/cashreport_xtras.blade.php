<style>
	.info-row .info-label {
		font-weight: 700;
		width: 180px;
	}
	.themodal{
		max-width: 980px;
		width: 100%;
	}
</style>

<div>
	<button class="mybtn primary w3-hide" id="save_daily_modal_trig" data-toggle="modal" data-target="#save_daily_modal">
		<i class="fas fa-plus"></i>
		Add New
	</button>

	<div class="modal fade" id="save_daily_modal" tabindex="-1" role="dialog" aria-labelledby="save_daily_modal_title" aria-hidden="true" style="z-index: 102;">
		<div class="modal-dialog modal-lg themodal" role="document">
			<div class="modal-content themeround borderless">
				<div class="modal-header">
					<span class="h3 modal-title" id="save_daily_modal_title">Daily Summary</span>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div id="dailyReportContainer"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="mybtn secondary" data-dismiss="modal">Cancel</button>
					<!-- <button type="button" class="mybtn primary" data-submitme="#save_daily_form">Save</button> -->
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	let report_sect_errshown = false;
	window['daily_report_data'] = {};
	window['myaddition'] = [
		'just_render_daily_report',
		'render_required_data',
		'actual_renderer',
		'save_daily_report',
		'print_daily_report',
		'print_current_setup',
		'just_render_debtors',
		'render_the_debtors',
		'renderDebtors',
		'DebtorsUI',
	];

	window['just_render_daily_report'] = () => {
		window['daily_report_data'] = {};
		render_required_data();
	};
	window['render_required_data'] = () => {
		save_daily_modal_trig.click();

		let loadtym = daily_report_data.daily_report_loadtime;
		if(loadtym == undefined){
			daily_report_data = {};
		}

		dailyReportContainer.innerHTML = `
			${mekLoader()}
		`;

		let container = dailyReportContainer;
		let Trange = window['timerange'] || 'today';

		const step1 = () => {
			// get processed data from backend
			window[fetch_bypass](linkAtlas['get_daily_report'],{t_range: Trange},'POST').then(dta => {
				if(!dta.success || !dta.result){
					throw new Error(dta.message);
				}

				responseHandler(dta, step2, dta);
			}).catch(err => {
				outht = `There was an error fetching Daily report information: <br><b class="${err.result !== undefined ? 'w3-text-orange' : 'w3-text-red'}">${err.message}</b>`;
				container.innerHTML = mekError('Error loading daily report', outht);
				report_sect_errshown = true;
			})
		}

		const step2 = (d) => {
			daily_report_data.daily_report = d.sendme;
			daily_report_data.daily_report_loadtime = new Date();

			actual_renderer(d.sendme,Trange);
		}

		if(loadtym == undefined){
			step1();
			return;
		}

		if(getDateDiff(daily_report_data.daily_report_loadtime,new Date()) > 22){
			step1();
		} else {
			step2({sendme: daily_report_data.daily_report});
		}
	}

	window['actual_renderer'] = (d,range = 'today') => {
		let container = dailyReportContainer;
		let drd = d;

		if(drd == undefined){
			alert_danger('invalid `actual_renderer` function call');
			return;
		}
		alert_info('rendering...');

		outht = `
			renderin...
		`;

		// top finances section
		let xtra = drd.time_difference !== undefined ? {
			"time_since_last_report" : `${formatNumber(drd.time_difference) || '--'} ${plural('hour',Math.floor(Number(drd.time_difference)))}`,
		} : {};
		let toshow_1 = {
			...xtra,
			"total_owed" : `<b>${c_currency} ${formatNumber(drd.total_owed)}</b>`,
			"carry_over" : `<b>${c_currency} ${formatNumber(drd.carry_over)}</b>`,
			"total_income" : `<b>${c_currency} ${formatNumber(drd.total_income)}</b>`,
			"cash_from_debtors" : `<b>${c_currency} ${formatNumber(drd.cash_from_debtors)}</b>`,
			"total_daily_cash" : `<b>${c_currency} ${formatNumber(drd.total_daily_cash)}</b>`,
			"total_cash" : `<b class="themetxt">${c_currency} ${formatNumber(drd.total_cash)}</b>`,
			"expenditure" : `<b class="w3-text-orange">${c_currency} ${formatNumber(drd.expenditure)}</b>`,
			"total_balance_available" : `<b class="w3-text-green">${c_currency} ${formatNumber(drd.total_balance_forward)}</b>`,
		};
		let deets_1_outht = '';

		Object.keys(toshow_1).forEach(f => {
			deets_1_outht += `
				<div class="info-row">
					<span class="info-label">${f.split("_").join(' ')}:</span>
					<span class="info-value">${toshow_1[f]}</span>
				</div>
			`;
		});

		// samples subsection
		let toshow_2 = {
			"total_samples_delivered" : formatNumber(drd.samples_count),
			"soil samples" : formatNumber(drd.sample_data['soil']),
			"rock samples" : formatNumber(drd.sample_data['rock']),
			"solution samples" : formatNumber(drd.sample_data['solution']),
			"carbon samples" : formatNumber(drd.sample_data['carbon']),
			"carbon activity samples" : formatNumber(drd.sample_data['carbon activity']),
			"monthly samples" : drd.sample_data['monthly'] != undefined ? formatNumber(drd.sample_data['monthly']) : '--',
		};
		let samples_outht = '';

		Object.keys(toshow_2).forEach(f => {
			samples_outht += range == 'today' || true ?
			`
				<div class="info-row">
					<span class="info-label">${f.split("_").join(' ')}:</span>
					<span class="info-value"><b>${toshow_2[f]}</b></span>
				</div>
			` : '';
		});


		// cash flow subsection
		let toshow_3 = {
			"income via mpesa" : `<b>${c_currency} ${formatNumber(drd.cash_data["income mpesa"])}</b>`,
			"income via cash" : `<b>${c_currency} ${formatNumber(drd.cash_data["income cash"])}</b>`,
			"debt paid via mpesa" : `<b>${c_currency} ${formatNumber(drd.cash_data["debt mpesa"])}</b>`,
			"debt paid via cash" : `<b>${c_currency} ${formatNumber(drd.cash_data["debt cash"])}</b>`,
			"total mpesa" : `<b>${c_currency} ${formatNumber(drd.cash_data["total mpesa"])}</b>`,
			"total cash" : `<b>${c_currency} ${formatNumber(drd.cash_data["total cash"])}</b>`,
		};
		let deets_3_outht = '';

		Object.keys(toshow_3).forEach(f => {
			deets_3_outht += `
				<div class="info-row">
					<span class="info-label">${f.split("_").join(' ')}:</span>
					<span class="info-value">${toshow_3[f]}</span>
				</div>
			`;
		});

		// system usage subsection
		let newxtra = drd.paid_tasks == undefined ? {} : {
			"paid_tasks" : drd.paid_tasks,
			"unpaid_tasks" : drd.unpaid_tasks,
		};
		let toshow_4 = {
			"users_registered" : drd.users_count,
			"clients_registered" : drd.clients_count,
			...newxtra,
			"tasks_created" : drd.tasks_count,
			"samples_delivered" : drd.samples_count,
			"task payments" : drd.total_payments_task_count,
			"invoice payments" : drd.total_payments_invoice_count,
			"reports_generated" : drd.reports_count,
			"invoices_generated" : drd.invoices_count,
			"expense_records entered" : drd.expenses_count,
		};
		let deets_4_outht = '';

		Object.keys(toshow_4).forEach(f => {
			deets_4_outht += `
				<div class="info-row">
					<span class="info-label">${f.split("_").join(' ')}:</span>
					<span class="info-value"><b>${toshow_4[f]}</b></span>
				</div>
			`;
		});


		// system usage subsection
			let gen_from_spend_Data = {};
			Object.keys(drd.spend_data).forEach(s => {
				gen_from_spend_Data[s] = `${c_currency} ${formatNumber(drd.spend_data[s])}`;
			});
			let toshow_5 = {
				// "Expenditure Entries" : `${formatNumber(drd.expenses_count)}`,
				...gen_from_spend_Data,
				"Total Expenditure" : `<b class="w3-text-orange">${c_currency} ${formatNumber(drd.expenditure)}</b>`,
			};
			let deets_5_outht = '';

			Object.keys(toshow_5).forEach(f => {
				deets_5_outht += `
					<div class="info-row">
						<span class="info-label">${f.split("_").join(' ')}:</span>
						<span class="info-value"><b>${toshow_5[f]}</b></span>
					</div>
				`;
			});

		// debt payments subsection
			let debtpay_outht = `
				<table class="mytable">
					<thead>
						<tr>
							<th>${mekSpan('Name')}</th>
							<th>${mekSpan('Contact')}</th>
							<th>${mekSpan('unpaid invoices')}</th>
							<th>${mekSpan('Total Paid')}</th>
							<th>${mekSpan('Balance')}</th>
						</tr>
					</thead>
					<tbody>
			`;

			if(drd.debt_pay_data !== undefined && drd.debt_pay_data.length > 0){
				let tot_balance = 0;
				drd.debt_pay_data.forEach(f => {
					debtpay_outht += `
						<tr>
							<td><span style="font-size: 1.1rem;">${f.client_name}</span></td>
							<td class="w3-right-align"><strong>${processNumber(f.client_contact)}</strong></td>
							<td class="w3-right-align"><strong>${formatNumber(f.invoices_count)}</strong></td>
							<td class="w3-right-align"><strong>${c_currency} ${formatNumber(f.payment_amount)}</strong></td>
							<td class="w3-right-align"><strong>${c_currency} ${formatNumber(f.credit_balance,2)}</strong></td>
						</tr>
					`;

					tot_balance += Number(f.credit_balance);
				});

				debtpay_outht += `
						<tr>
							<td colspan="3"><b style="font-size: 1.1rem;">Total</b></td>
							<td class="themetxt w3-right-align"><strong>${c_currency} ${formatNumber(drd.cash_from_debtors,2)}</strong></td>
							<td class="themetxt w3-right-align"><strong>${c_currency} ${formatNumber(tot_balance,2)}</strong></td>
						</tr>
					</tbody>
				</table>
				`;
			} else {
				debtpay_outht += `
						<tr>
							<td colspan="12">
								<span style="font-size: 1.1rem;">
									<i>${drd.created_at == undefined ? 'no debt payments found' : 'no debt payments recorded'}</i>
								</span>
							</td>
						</tr>
					</tbody>
				</table>
				`;
			}

		// system usage subsection
			let debtors_outht = `
				<table class="mytable">
					<thead>
						<tr>
							<th>${mekSpan('Name')}</th>
							<th>${mekSpan('Contact')}</th>
							<th>${mekSpan('unpaid invoices')}</th>
							<th>${mekSpan('Total Owed')}</th>
						</tr>
					</thead>
					<tbody>
			`;

			if(drd.debtors !== undefined && drd.debtors.length > 0){
				let tdt = 0;

				drd.debtors.forEach(f => {
					tdt += Number(f.credit_balance);

					debtors_outht += `
						<tr>

							<td><span style="font-size: 1.1rem;">${f.client_name}</span></td>
							<td class="w3-right-align"><strong>${processNumber(f.client_contact)}</strong></td>
							<td class="w3-right-align"><strong>${formatNumber(f.invoices_count)}</strong></td>
							<td><strong>${c_currency} ${formatNumber(f.credit_balance,2)}</strong></td>
						</tr>
					`;
				});

				debtors_outht += `
						<tr>
							<td colspan="3"><b style="font-size: 1.1rem;">Total</b></td>
							<td><strong class="themetxt">${c_currency} ${formatNumber(tdt,2)}</strong></td>
						</tr>
					</tbody>
				</table>
				`;
			} else {
				debtors_outht += `
						<tr>
							<td colspan="12">
								<span style="font-size: 1.1rem;">
									<i>${drd.created_at == undefined ? 'no debtors found' : 'no debtors recorded'}</i>
								</span>
							</td>
						</tr>
					</tbody>
				</table>
				`;
			}

		// visual hints to show system info
		let thedate = drd.created_at == undefined ? `<b class="themetxt">${range.replace('_',' ')}</b>` : `<b class="themetxt">${formatDate(new Date(drd.created_at))}</b>`;
		let btns = drd.created_at == undefined ? `
			<button class="mybtn primary ${window['is_admin_page'] ? 'is_ghost' : ''}" data-runme="save_daily_report"><i class="fa fa-save"></i> Save Cash Report</button>
		` : `
			<button class="mybtn primary" data-runme="print_daily_report" data-myid="${drd.id}" data-mytype="${drd.rectype || 'daily'}"><i class="fa fa-print"></i> Print Report</button>
		`;
		let extrabtn = drd.created_at == undefined ? `
			<button class="mybtn primary" data-runme="print_current_setup" data-myid="${drd.id}" data-mytype="${drd.rectype || 'daily'}"><i class="fa fa-print"></i> Print Current Setup</button>
		` : '';
		save_daily_modal_title.innerHTML = `
			${drd.rectype || 'Daily'} summary for ${thedate}
		`;
		outht = `
			<div class="sample-info-card w3-hide_">
				<h5><i class="${iconAtlas['cash_reports']}"></i> Cash summary</h5>
				${deets_1_outht}
			</div>

			<div class="distance-sm border themeround collapser">
				<a class="text-decoration-none spacy-sm flowline spread centerline" href="#sampleInfo" data-toggle="collapse" data-target="#sampleInfo">
					<span class="text-uppercase text-muted font-weight-bold"><i class="${iconAtlas['samples']}"></i> Samples Information</span>
					<i class="fa fa-chevron-down myicon"></i>
				</a>
				<div class="collapse panelbg distance-sm spacy-sm" id="sampleInfo">
					${samples_outht}
				</div>
			</div>

			<div class="distance-sm border themeround collapser">
				<a class="text-decoration-none spacy-sm flowline spread centerline" href="#cashInfo" data-toggle="collapse" data-target="#cashInfo">
					<span class="text-uppercase text-muted font-weight-bold"><i class="fa fa-money-bill"></i> Cash channels report</span>
					<i class="fa fa-chevron-down myicon"></i>
				</a>
				<div class="collapse panelbg distance-sm spacy-sm" id="cashInfo">
					${deets_3_outht}
				</div>
			</div>

			<div class="distance-sm border themeround collapser">
				<a class="text-decoration-none spacy-sm flowline spread centerline" href="#spendInfo" data-toggle="collapse" data-target="#spendInfo">
					<span class="text-uppercase text-muted font-weight-bold"><i class="${iconAtlas['expenses']}"></i> Expenditure data report</span>
					<i class="fa fa-chevron-down myicon"></i>
				</a>
				<div class="collapse panelbg distance-sm spacy-sm" id="spendInfo">
					${deets_5_outht}
				</div>
			</div>

			<div class="distance-sm border themeround collapser">
				<a class="text-decoration-none spacy-sm flowline spread centerline" href="#systemInfo" data-toggle="collapse" data-target="#systemInfo">
					<span class="text-uppercase text-muted font-weight-bold"><i class="fas fa-info-circle"></i> System Usage Report</span>
					<i class="fa fa-chevron-down myicon"></i>
				</a>
				<div class="collapse panelbg distance-sm spacy-sm" id="systemInfo">
					${deets_4_outht}
				</div>
			</div>

			<div class="distance-sm border themeround collapser">
				<a class="text-decoration-none spacy-sm flowline spread centerline" href="#debtpayInfo" data-toggle="collapse" data-target="#debtpayInfo">
					<span class="text-uppercase text-muted font-weight-bold"><i class="${iconAtlas['invoices']}"></i> Debt payments</span>
					<i class="fa fa-chevron-down myicon"></i>
				</a>
				<div class="collapse panelbg distance-sm spacy-sm" id="debtpayInfo">
					${debtpay_outht}
				</div>
			</div>

			<div class="distance-sm border themeround collapser">
				<a class="text-decoration-none spacy-sm flowline spread centerline" href="#debtorInfo" data-toggle="collapse" data-target="#debtorInfo">
					<span class="text-uppercase text-muted font-weight-bold"><i class="${iconAtlas['clients']}"></i> Debtors</span>
					<i class="fa fa-chevron-down myicon"></i>
				</a>
				<div class="collapse panelbg distance-sm spacy-sm" id="debtorInfo">
					${debtors_outht}
				</div>
			</div>

			<div class="flowline left gap-sm distance-sm">
		`;

		if(!window['is_admin_page']){
			outht += `
				${btns}
			`;
		}

		outht += `
				${extrabtn}
			</div>
		`;

		container.innerHTML = outht;

		refreshUI(300);
	};

	window['save_daily_report'] = (btn) => {
		alert_info('saving daily report');

		const step1 = () => {
			window[fetch_bypass](linkAtlas['save_daily_report'],{rptdata: daily_report_data.daily_report},'POST').then((d) => {
				responseHandler(d,step2,d);
			});
		}

		const step2 = (d) => {
			// alert_success('saved')
		}

		step1();
	}
	window['print_daily_report'] = (btn) => {
		window[variableAtlas['cash_report_id']] = btn.dataset.myid;
		window[variableAtlas['cash_report_type']] = btn.dataset.mytype;
		loadPage({template: linkAtlas['print_cash_report'], sidenote: pageNames['print_cash_report']});
	}

	window['print_current_setup'] = (btn) => {
		window[variableAtlas['cash_report_id']] = btn.dataset.myid;
		window[variableAtlas['cash_report_type']] = btn.dataset.mytype;
		if(daily_report_data.daily_report.context == undefined){
			daily_report_data.daily_report.context = formatDate(new Date());
		}
		window[variableAtlas['cash_report_data']] = daily_report_data.daily_report;
		loadPage({template: linkAtlas['print_cash_report'], sidenote: pageNames['print_cash_report']});
	}

	setTimeout(() => {
		window['killOnLeave'] = window['killOnLeave'] == undefined ? [] : killOnLeave;
		killlist = killOnLeave;
		killlist = [...killlist, ...myaddition];

		window['killOnLeave'] = killlist;
	},randomRange(300,500));
</script>

