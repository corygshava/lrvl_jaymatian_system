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

	window['render_required_data'] = () => {
		save_daily_modal_trig.click();
		daily_report_data = {};

		dailyReportContainer.innerHTML = `
			${mekLoader()}
		`;

		let container = dailyReportContainer;

		const step1 = () => {
			// get processed data from backend
			window[fetch_bypass](linkAtlas['get_daily_report'],{},'POST').then(dta => {
				if(!dta.success || !dta.result){
					throw new Error(dta.message);
				}

				responseHandler(dta, step2, dta);
			}).catch(err => {
				outht = `There was an error fetching System information: <br><b class="${err.result !== undefined ? 'w3-text-orange' : 'w3-text-red'}">${err.message}</b>`;
				container.innerHTML = mekError('Error loading daily report', outht);
				report_sect_errshown = true;
			})
		}

		const step2 = (d) => {
			daily_report_data.daily_report = d.sendme;

			actual_renderer(d.sendme);
		}

		step1();
	}

	window['actual_renderer'] = (d) => {
		let container = dailyReportContainer;
		let drd = d;

		alert_info('rendering...');

		outht = `
			renderin...
		`;

		// top finances section
		let toshow_1 = {
			"time_since_last_report" : `${formatNumber(drd.time_difference, 2) || '--'} hours`,
			"carry_over" : `<b>KES ${formatNumber(drd.carry_over)}</b>`,
			"total_income" : `<b>KES ${formatNumber(drd.total_income)}</b>`,
			"cash_from_debtors" : `<b>KES ${formatNumber(drd.cash_from_debtors)}</b>`,
			"total_daily_cash" : `<b>KES ${formatNumber(drd.total_daily_cash)}</b>`,
			"total_cash_income" : `<b class="w3-text-green">KES ${formatNumber(drd.total_cash)}</b>`,
			"expenditure" : `<b class="w3-text-orange">KES ${formatNumber(drd.expenditure)}</b>`,
			"total_balance_forward" : `<b class="w3-text-black">KES ${formatNumber(drd.total_balance_forward)}</b>`,
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
		};
		let deets_2_outht = '';

		Object.keys(toshow_2).forEach(f => {
			deets_2_outht += `
				<div class="info-row">
					<span class="info-label">${f.split("_").join(' ')}:</span>
					<span class="info-value"><b>${toshow_2[f]}</b></span>
				</div>
			`;
		});


		// cash flow subsection
		let toshow_3 = {
			"income via mpesa" : `<b>KES ${formatNumber(drd.cash_data["income mpesa"])}</b>`,
            "income via cash" : `<b>KES ${formatNumber(drd.cash_data["income cash"])}</b>`,
            "debt paid via mpesa" : `<b>KES ${formatNumber(drd.cash_data["debt mpesa"])}</b>`,
            "debt paid via cash" : `<b>KES ${formatNumber(drd.cash_data["debt cash"])}</b>`,
            "total mpesa" : `<b>KES ${formatNumber(drd.cash_data["total mpesa"])}</b>`,
            "total cash" : `<b>KES ${formatNumber(drd.cash_data["total cash"])}</b>`,
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
		let toshow_4 = {
			"users_registered" : drd.users_count,
			"clients_registered" : drd.clients_count,
			"paid_tasks" : drd.paid_tasks,
			"unpaid_tasks" : drd.unpaid_tasks,
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
		let toshow_5 = {
			"Expenditure Entries" : `${formatNumber(drd.expenses_count)}`,
			"director expenditure" : `${formatNumber(drd.spend_data["director expenditure"])}`,
			"Bank transfer" : `${formatNumber(drd.spend_data["Bank transfer"])}`,
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

		let thedate = drd.created_at == undefined ? 'Today' : formatDate0(drd.created_at);
		let btns = `
			<button class="mybtn primary" data-runme="save_daily_report"><i class="fa fa-save"></i> Save Report</button>
		`;
		save_daily_modal_title.innerHTML = `
			Daily summary for ${thedate}
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
					${deets_2_outht}
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
			<div class="distance-sm">
				${btns}
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
</script>
