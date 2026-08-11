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

<style>
	/* ============================================================
		 Debtors Ledger UI  ·  debtors.css
		 All styles scoped under .dlt — safe to inject anywhere.
		 ============================================================ */

	.dlt {
		--ink:        #0c131c;
		--panel:      #141e2a;
		--panel-2:    #182432;
		--line:       #26374b;
		--line-soft:  #1d2b3b;
		--text:       #e8eef6;
		--muted:      #9db0c4;
		--faint:      #64788e;
		--amber:      #ffb454;
		--amber-soft: rgba(255,180,84,.12);
		--teal:       #3ad0ae;
		--teal-soft:  rgba(58,208,174,.12);
		--blue:       #5aa9ff;
		--mauve:      #c98bff;
		--red:        #ff7d7d;

		position: relative;
		overflow: hidden;
		isolation: isolate;
		font-family: "IBM Plex Sans", "Segoe UI", sans-serif;
		font-size: 15px;
		color: var(--text);
		border: 1px solid var(--line);
		border-radius: 14px;
		padding-bottom: 18px;
		background:
			radial-gradient(900px 340px at 85% -10%, rgba(255,180,84,.08), transparent 60%),
			radial-gradient(700px 300px at -10% 0%,  rgba(58,208,174,.07), transparent 55%),
			linear-gradient(180deg, #101927 0%, var(--ink) 100%);
	}

	/* faint ledger rules behind everything */
	.dlt::before {
		content: "";
		position: absolute;
		inset: 0;
		z-index: 0;
		pointer-events: none;
		background: repeating-linear-gradient(0deg, transparent 0 31px, rgba(255,255,255,.022) 31px 32px);
	}
	.dlt > * { position: relative; z-index: 1; }

	/* ---------- entrance ---------- */
	@keyframes dltIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }
	.dlt-head    { animation: dltIn .5s .02s both; }
	.dlt-warn    { animation: dltIn .5s .08s both; }
	.dlt-stats   { animation: dltIn .5s .10s both; }
	.dlt-toolbar { animation: dltIn .5s .16s both; }
	.dlt-scroll, .dlt-empty-static { animation: dltIn .5s .22s both; }
	.dlt-foot    { animation: dltIn .5s .28s both; }
	.dlt-log     { animation: dltIn .5s .34s both; }

	/* ---------- header band ---------- */
	.dlt-head {
		display: flex;
		justify-content: space-between;
		align-items: flex-end;
		gap: 24px;
		flex-wrap: wrap;
		padding: 26px 28px 22px;
		border-bottom: 1px solid var(--line-soft);
	}
	.dlt-eyebrow {
		display: flex; align-items: center; gap: 8px;
		font-size: 11px; font-weight: 600;
		letter-spacing: .22em; text-transform: uppercase;
		color: var(--teal);
	}
	.dlt-title {
		font-family: "Barlow Condensed", sans-serif;
		font-weight: 700;
		font-size: clamp(30px, 4vw, 42px);
		line-height: 1.05;
		margin: 8px 0 8px;
		letter-spacing: .01em;
	}
	.dlt-msg { display: flex; align-items: center; gap: 8px; margin: 0; font-size: 13.5px; color: var(--muted); }
	.dlt-pill {
		display: inline-flex; align-items: center; gap: 6px;
		font-size: 11px; font-weight: 600; letter-spacing: .06em;
		padding: 3px 10px; border-radius: 20px; border: 1px solid;
	}
	.dlt-pill--ok   { color: var(--teal); background: var(--teal-soft); border-color: rgba(58,208,174,.35); }
	.dlt-pill--bad  { color: var(--red);  background: rgba(255,125,125,.1); border-color: rgba(255,125,125,.4); }

	.dlt-total-label { text-align: right; font-size: 11px; letter-spacing: .2em; text-transform: uppercase; color: var(--muted); }
	.dlt-total-label i { color: var(--amber); margin-right: 6px; }
	.dlt-total-num {
		font-family: "Barlow Condensed", sans-serif;
		font-weight: 700;
		font-size: clamp(40px, 5vw, 58px);
		line-height: 1;
		color: var(--amber);
		text-shadow: 0 0 34px rgba(255,180,84,.25);
	}
	.dlt-total-num span { font-size: .55em; vertical-align: .35em; margin-right: 4px; opacity: .8; }
	.dlt-total-sub { text-align: right; margin-top: 6px; font-family: "IBM Plex Mono", monospace; font-size: 12px; color: var(--faint); }

	/* ---------- warning strip (success:false) ---------- */
	.dlt-warn {
		display: flex; align-items: center; gap: 10px;
		margin: 16px 28px 0; padding: 10px 14px;
		border: 1px solid rgba(255,125,125,.4);
		background: rgba(255,125,125,.08);
		color: #ffb3b3; border-radius: 8px; font-size: 13px;
	}

	/* ---------- stats band ---------- */
	.dlt-stats {
		display: grid;
		grid-template-columns: repeat(3, minmax(150px, auto)) 1.7fr;
		border-bottom: 1px solid var(--line-soft);
	}
	.dlt-stat {
		display: flex; align-items: center; gap: 12px;
		padding: 16px 22px;
		border-right: 1px solid var(--line-soft);
		transition: background .25s;
	}
	.dlt-stat:hover { background: rgba(255,255,255,.02); }
	.dlt-stat-ico { width: 38px; height: 38px; flex: none; border-radius: 10px; display: grid; place-items: center; font-size: 15px; }
	.dlt-ico-teal  { background: var(--teal-soft);  color: var(--teal); }
	.dlt-ico-amber { background: var(--amber-soft); color: var(--amber); }
	.dlt-ico-blue  { background: rgba(90,169,255,.12); color: var(--blue); }
	.dlt-stat-num { font-family: "Barlow Condensed", sans-serif; font-weight: 700; font-size: 26px; line-height: 1; }
	.dlt-stat-cap { margin-top: 3px; font-size: 10.5px; letter-spacing: .12em; text-transform: uppercase; color: var(--faint); }

	/* plan mix bar */
	.dlt-mix { display: flex; flex-direction: column; justify-content: center; gap: 10px; padding: 14px 22px; }
	.dlt-mix-track { display: flex; height: 10px; border-radius: 6px; overflow: hidden; background: var(--line-soft); }
	.dlt-mix-fill { width: 0; transition: width .9s cubic-bezier(.22,1,.3,1); }
	.dlt-mix-fill.c0 { background: var(--teal); }
	.dlt-mix-fill.c1 { background: var(--amber); }
	.dlt-mix-fill.c2 { background: var(--blue); }
	.dlt-mix-fill.c3 { background: var(--mauve); }
	.dlt-mix-legend { display: flex; flex-wrap: wrap; gap: 6px 16px; font-size: 12px; color: var(--muted); }
	.dlt-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; margin-right: 6px; vertical-align: 1px; }
	.dlt-dot.c0 { background: var(--teal); } .dlt-dot.c1 { background: var(--amber); }
	.dlt-dot.c2 { background: var(--blue); } .dlt-dot.c3 { background: var(--mauve); }
	.dlt-mix-legend b { color: var(--text); font-family: "IBM Plex Mono", monospace; font-weight: 500; }

	/* ---------- toolbar ---------- */
	.dlt-toolbar { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; padding: 18px 28px 14px; }
	.dlt-search { flex: 1 1 260px; max-width: 430px; }
	.dlt-plan   { flex: 0 0 auto; width: auto; min-width: 170px; }
	.dlt-count  { margin-left: auto; font-family: "IBM Plex Mono", monospace; font-size: 12px; color: var(--faint); }

	/* Bootstrap 4 controls, darkened */
	.dlt .form-control, .dlt .custom-select {
		height: 40px;
		background: var(--panel-2);
		border: 1px solid var(--line);
		color: var(--text);
		transition: border-color .2s, box-shadow .2s;
	}
	.dlt .form-control::placeholder { color: var(--faint); }
	.dlt .form-control:focus, .dlt .custom-select:focus {
		background: var(--panel-2);
		color: var(--text);
		border-color: var(--amber);
		box-shadow: 0 0 0 .2rem rgba(255,180,84,.15);
	}
	.dlt .input-group-text { background: var(--panel); border: 1px solid var(--line); color: var(--faint); }
	.dlt .custom-select {
		background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239db0c4' stroke-width='3'%3e%3cpath d='M6 9l6 6 6-6'/%3e%3c/svg%3e");
	}

	/* ---------- table ---------- */
	.dlt-scroll {
		max-height: 540px;
		overflow: auto;
		border-top: 1px solid var(--line-soft);
		border-bottom: 1px solid var(--line-soft);
		scrollbar-width: thin;
		scrollbar-color: var(--line) transparent;
	}
	.dlt-scroll::-webkit-scrollbar { width: 10px; height: 10px; }
	.dlt-scroll::-webkit-scrollbar-thumb { background: var(--line); border-radius: 8px; border: 2px solid var(--ink); }

	.dlt-table { margin: 0; color: var(--text); }
	.dlt-table thead th {
		position: sticky; top: 0; z-index: 2;
		background: var(--panel);
		color: var(--muted);
		font-size: 11px; font-weight: 600;
		letter-spacing: .14em; text-transform: uppercase;
		padding: 12px 16px;
		border-bottom: 1px solid var(--line);
		border-top: 0;
		white-space: nowrap;
	}
	.dlt-table thead th[data-dlt-sort] { cursor: pointer; user-select: none; transition: color .15s; }
	.dlt-table thead th[data-dlt-sort]:hover { color: var(--text); }
	.dlt-sortico { margin-left: 6px; color: var(--faint); }
	.dlt-table thead th.dlt-sorted { color: var(--amber); }
	.dlt-table thead th.dlt-sorted .dlt-sortico { color: var(--amber); }

	.dlt-table td { padding: 12px 16px; border-top: 1px solid var(--line-soft); vertical-align: middle; }
	.dlt-table tbody tr:first-child td { border-top: 0; }

	@keyframes dltRowIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: none; } }
	.dlt-row { animation: dltRowIn .4s both; transition: background .18s; }
	.dlt-row:hover { background: rgba(255,180,84,.05); }
	.dlt-row:hover td:first-child { box-shadow: inset 3px 0 0 var(--amber); }

	.dlt-idx { font-family: "IBM Plex Mono", monospace; font-size: 12px; color: var(--faint); }

	.dlt-client { display: flex; align-items: center; gap: 12px; min-width: 220px; }
	.dlt-avatar {
		width: 38px; height: 38px; flex: none; border-radius: 10px;
		display: grid; place-items: center;
		font-family: "Barlow Condensed", sans-serif; font-weight: 700; font-size: 15px; letter-spacing: .04em;
	}
	.dlt-avatar--0 { background: var(--teal-soft);  color: var(--teal); }
	.dlt-avatar--1 { background: var(--amber-soft); color: var(--amber); }
	.dlt-avatar--2 { background: rgba(90,169,255,.12); color: var(--blue); }
	.dlt-avatar--3 { background: rgba(201,139,255,.12); color: var(--mauve); }
	.dlt-name { font-weight: 600; font-size: 14.5px; }
	.dlt-g { margin-left: 5px; font-size: 12px; }
	.dlt-g--m { color: var(--blue); }
	.dlt-g--f { color: #ff8fa3; }
	.dlt-contact { margin-top: 2px; font-family: "IBM Plex Mono", monospace; font-size: 12px; color: var(--faint); }
	.dlt-contact i { margin-right: 5px; }

	.dlt-badge {
		display: inline-flex; align-items: center; gap: 6px;
		font-size: 11.5px; font-weight: 600; letter-spacing: .04em;
		padding: 5px 10px; border-radius: 20px; border: 1px solid; white-space: nowrap;
	}
	.dlt-badge--teal  { color: var(--teal);  background: var(--teal-soft);  border-color: rgba(58,208,174,.35); }
	.dlt-badge--amber { color: var(--amber); background: var(--amber-soft); border-color: rgba(255,180,84,.35); }
	.dlt-badge--blue  { color: var(--blue);  background: rgba(90,169,255,.12); border-color: rgba(90,169,255,.35); }
	.dlt-badge--mute  { color: var(--muted); background: rgba(255,255,255,.04); border-color: var(--line); }

	.dlt-balance { font-family: "IBM Plex Mono", monospace; font-weight: 600; font-size: 14.5px; color: var(--amber); }
	.dlt-balance--zero { color: var(--teal); }
	.dlt-balance-sub { font-size: 9.5px; letter-spacing: .2em; text-transform: uppercase; color: var(--faint); }
	.dlt-date { font-size: 12.5px; color: var(--muted); white-space: nowrap; }

	/* row actions — revealed on hover */
	.dlt-acts { display: flex; gap: 6px; justify-content: flex-end; opacity: .35; transition: opacity .2s; }
	.dlt-row:hover .dlt-acts { opacity: 1; }
	@media (hover: none) { .dlt-acts { opacity: 1; } }
	.dlt-act {
		width: 30px; height: 30px; border-radius: 8px;
		border: 1px solid var(--line); background: transparent; color: var(--muted);
		transition: all .18s;
	}
	.dlt-act:hover { border-color: var(--amber); color: var(--amber); background: var(--amber-soft); transform: translateY(-1px); }
	.dlt-act:focus-visible { outline: 2px solid var(--amber); outline-offset: 2px; }

	/* empty state */
	.dlt-empty { padding: 56px 20px; text-align: center; color: var(--faint); }
	.dlt-empty i { display: block; font-size: 34px; color: var(--line); margin-bottom: 12px; }
	.dlt-empty-static { margin: 0 28px; border: 1px dashed var(--line); border-radius: 10px; }

	/* ---------- footer / pager ---------- */
	.dlt-foot { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; padding: 14px 28px 4px; }
	.dlt-showing { font-family: "IBM Plex Mono", monospace; font-size: 12px; color: var(--faint); }
	.dlt .pagination { margin: 0; gap: 4px; }
	.dlt-pager .page-link {
		min-width: 34px; text-align: center; padding: .35rem .55rem;
		font-size: 13px; border-radius: 8px;
		background: transparent; border: 1px solid var(--line); color: var(--muted);
		transition: all .15s;
	}
	.dlt-pager .page-link:hover { border-color: var(--amber); color: var(--amber); background: transparent; }
	.dlt-pager .page-item.active .page-link { background: var(--amber); border-color: var(--amber); color: #20160a; font-weight: 700; }
	.dlt-pager .page-item.disabled .page-link { background: transparent; border-color: transparent; opacity: .4; }
	.dlt-pager .dlt-gap .page-link { border: none; pointer-events: none; }
	.dlt-pager .page-link:focus { box-shadow: 0 0 0 .2rem rgba(255,180,84,.15); }

	/* ---------- run log console ---------- */
	.dlt-log { margin: 16px 28px 4px; border: 1px solid var(--line-soft); border-radius: 10px; background: rgba(9,14,20,.55); }
	.dlt-log-toggle {
		width: 100%; display: flex; align-items: center; gap: 10px;
		padding: 11px 16px; border: 0; background: none; cursor: pointer;
		font: inherit; font-size: 13px; color: var(--muted); transition: color .15s;
	}
	.dlt-log-toggle:hover { color: var(--text); }
	.dlt-log-toggle i.fa-terminal { color: var(--teal); }
	.dlt-log-n { font-family: "IBM Plex Mono", monospace; font-size: 11px; color: var(--faint); background: var(--panel-2); border: 1px solid var(--line); padding: 1px 8px; border-radius: 12px; }
	.dlt-chev { margin-left: auto; font-size: 11px; transition: transform .25s; }
	.dlt-log.open .dlt-chev { transform: rotate(180deg); }
	.dlt-log-body { border-top: 1px dashed var(--line); padding: 12px 16px; font-family: "IBM Plex Mono", monospace; font-size: 12.5px; }
	.dlt-log-line { display: flex; gap: 12px; padding: 3px 0; color: var(--muted); animation: dltRowIn .5s both; }
	.dlt-log-i { color: var(--faint); }
	.dlt-log-caret { color: var(--teal); }
	.dlt-log-line--last { color: var(--teal); }

	/* ---------- responsive ---------- */
	@media (max-width: 900px) {
		.dlt-stats { grid-template-columns: 1fr 1fr; }
		.dlt-mix { grid-column: 1 / -1; border-top: 1px solid var(--line-soft); }
	}
	@media (max-width: 620px) {
		.dlt-head, .dlt-toolbar, .dlt-foot { padding-left: 16px; padding-right: 16px; }
		.dlt-log, .dlt-warn { margin-left: 16px; margin-right: 16px; }
		.dlt-col-added { display: none; }   /* drop the "Added" column on phones */
		.dlt-head { align-items: flex-start; flex-direction: column; }
		.dlt-total-label, .dlt-total-sub { text-align: left; }
	}

	@media (prefers-reduced-motion: reduce) {
		.dlt *, .dlt *::before { animation: none !important; transition: none !important; }
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

	<button class="mybtn primary w3-hide" id="view_debtors_modal_trig" data-toggle="modal" data-target="#view_debtors_modal">
		<i class="fas fa-plus"></i>
		view_debts
	</button>

	<div class="modal fade" id="view_debtors_modal" tabindex="-1" role="dialog" aria-labelledby="view_debtors_modal_title" aria-hidden="true" style="z-index: 102;">
		<div class="modal-dialog modal-lg themodal" role="document">
			<div class="modal-content themeround borderless">
				<div class="modal-header">
					<span class="h3 modal-title" id="view_debtors_modal_title">All debtors</span>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div id="viewDebtsUI"></div>
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
			"total_owed" : `<b>KES ${formatNumber(drd.total_owed)}</b>`,
			"carry_over" : `<b>KES ${formatNumber(drd.carry_over)}</b>`,
			"total_income" : `<b>KES ${formatNumber(drd.total_income)}</b>`,
			"cash_from_debtors" : `<b>KES ${formatNumber(drd.cash_from_debtors)}</b>`,
			"total_daily_cash" : `<b>KES ${formatNumber(drd.total_daily_cash)}</b>`,
			"total_cash" : `<b class="themetxt">KES ${formatNumber(drd.total_cash)}</b>`,
			"expenditure" : `<b class="w3-text-orange">KES ${formatNumber(drd.expenditure)}</b>`,
			"total_balance_available" : `<b class="w3-text-green">KES ${formatNumber(drd.total_balance_forward)}</b>`,
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
				gen_from_spend_Data[s] = `KES ${formatNumber(drd.spend_data[s])}`;
			});
			let toshow_5 = {
				// "Expenditure Entries" : `${formatNumber(drd.expenses_count)}`,
				...gen_from_spend_Data,
				"Total Expenditure" : `<b class="w3-text-orange">KES ${formatNumber(drd.expenditure)}</b>`,
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
							<td class="w3-right-align"><strong>KES ${formatNumber(f.payment_amount)}</strong></td>
							<td class="w3-right-align"><strong>KES ${formatNumber(f.credit_balance,2)}</strong></td>
						</tr>
					`;

					tot_balance += Number(f.credit_balance);
				});

				debtpay_outht += `
						<tr>
							<td colspan="3"><b style="font-size: 1.1rem;">Total</b></td>
							<td class="themetxt w3-right-align"><strong>KES ${formatNumber(drd.cash_from_debtors,2)}</strong></td>
							<td class="themetxt w3-right-align"><strong>KES ${formatNumber(tot_balance,2)}</strong></td>
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
							<td><strong>KES ${formatNumber(f.credit_balance,2)}</strong></td>
						</tr>
					`;
				});

				debtors_outht += `
						<tr>
							<td colspan="3"><b style="font-size: 1.1rem;">Total</b></td>
							<td><strong class="themetxt">KES ${formatNumber(tdt,2)}</strong></td>
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

	window['just_render_debtors'] = (btn) => {
		window['all_debtors'] = [];
		render_the_debtors();
	}
	window['render_the_debtors'] = () => {
		alert_info('rendering the debtors');
		view_debtors_modal_trig.click();

		let debtui = document.querySelector('#viewDebtsUI');
		debtui.innerHTML = `
			${mekLoader()}
		`;

		let container = debtui;
		let Trange = window['timerange'] || 'today';

		const step1 = () => {
			// load the debtors from the system
			window[fetch_bypass](linkAtlas['get_all_debtors'],{t_range: Trange},'POST').then(dta => {
				if(!dta.success || !dta.result){
					throw new Error(dta.message);
				}

				responseHandler(dta, step2, dta);
			}).catch(err => {
				outht = `There was an error fetching Debtors information: <br><b class="${err.result !== undefined ? 'w3-text-orange' : 'w3-text-red'}">${err.message}</b>`;
				container.innerHTML = mekError('Error loading daily report', outht);
				report_sect_errshown = true;
			})
		}

		const step2 = (d) => {
			// render the recieved data
			// alert_info('rendering data');
			let outht = mekStandin('total owed: ' + mekSpan(formatNumber(d.sendme.total_owed,2)));
			console.log(d);

			// outht = renderDebtors(d);

			DebtorsUI.mount(container, d);
			// container.innerHTML = outht;
		}

		step1();
	}

	setTimeout(() => {
		window['killOnLeave'] = window['killOnLeave'] == undefined ? [] : killOnLeave;
		killlist = killOnLeave;
		killlist = [...killlist,...myaddition];

		window['killOnLeave'] = killlist;
	},randomRange(300,500));
</script>

<script>
	// from qwen
	/* ============================================================
	 Debtors Ledger UI  ·  debtors.js
	 ------------------------------------------------------------
	 API:
		 renderDebtors(data, opts?)        -> returns an HTML string
		 DebtorsUI.activate(el, opts?)     -> wires a rendered string
		 DebtorsUI.mount(el, data, opts?)  -> inject + wire in one call

	 Options:
		 currency   "$"            symbol shown on totals/balances
		 pageSize   25             rows per page
		 onAction   null           fn(action, debtor, rowEl) — "view" | "edit"

	 Notes:
		 - Rows are rendered page-by-page, so hundreds/thousands of
			 debtors stay fast (only ~25 <tr> exist at any moment).
		 - All user-supplied text is HTML-escaped.
	 ============================================================ */

	(function () {
		// "use strict";

		var DEFAULTS = { currency: "KSH ", pageSize: 25, onAction: null };
		var MIX_COLORS = 4;

		/* ---------- helpers ---------- */
		function esc(v) {
			return String(v == null ? "" : v).replace(/[&<>"']/g, function (c) {
				return { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c];
			});
		}
		function toNum(v) { var n = parseFloat(v); return isFinite(n) ? n : 0; }
		function money(n) {
			return n.toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
		}
		function shortDate(iso) {
			if (!iso) return "—";
			var d = new Date(iso);
			return isNaN(d) ? "—" : d.toLocaleDateString("en-US", { month: "short", day: "numeric", year: "numeric" });
		}
		function debounce(fn, ms) {
			var t;
			return function () {
				var args = arguments, ctx = this;
				clearTimeout(t);
				t = setTimeout(function () { fn.apply(ctx, args); }, ms);
			};
		}
		function initials(name) {
			var p = String(name || "?").trim().split(/\s+/);
			return ((p[0] || "?").charAt(0) + (p[1] ? p[1].charAt(0) : "")).toUpperCase();
		}
		function hueOf(name) {
			var h = 0, s = String(name || "");
			for (var i = 0; i < s.length; i++) h += s.charCodeAt(i);
			return h % 4;
		}

		var PLAN_META = {
			monthly:   { label: "Monthly",   cls: "dlt-badge--teal",  icon: "fa-calendar-alt" },
			per_visit: { label: "Per visit", cls: "dlt-badge--amber", icon: "fa-stethoscope" }
		};
		function planMeta(p) {
			return PLAN_META[p] || { label: p || "unspecified", cls: "dlt-badge--mute", icon: "fa-tag" };
		}
		function genderIcon(g) {
			if (g === "male")   return '<i class="fas fa-mars dlt-g dlt-g--m" title="male"></i>';
			if (g === "female") return '<i class="fas fa-venus dlt-g dlt-g--f" title="female"></i>';
			return "";
		}

		/* ---------- model ---------- */
		function buildModel(data) {
			var d = data || {};
			var send = d.sendme || {};
			var debtors = Array.isArray(send.debtors) ? send.debtors.slice() : [];
			debtors.forEach(function (x, i) { x.__i = i; }); // stable index for callbacks

			var sum = debtors.reduce(function (a, x) { return a + toNum(x.credit_balance); }, 0);
			var hasTotal = send.total_owed != null && send.total_owed !== "";
			var plans = {};
			debtors.forEach(function (x) {
				var k = x.payment_plan || "unspecified";
				plans[k] = (plans[k] || 0) + 1;
			});

			return {
				ok: d.success !== false,
				message: d.message || "",
				runlog: Array.isArray(d.runlog) ? d.runlog : [],
				debtors: debtors,
				count: debtors.length,
				total: hasTotal ? toNum(send.total_owed) : sum,
				sum: sum,
				avg: debtors.length ? sum / debtors.length : 0,
				max: debtors.reduce(function (m, x) { return Math.max(m, toNum(x.credit_balance)); }, 0),
				plans: plans
			};
		}

		/* ---------- fragments ---------- */
		function statTile(icoCls, icon, num, cap) {
			return '<div class="dlt-stat">' +
				'<span class="dlt-stat-ico ' + icoCls + '"><i class="fas ' + icon + '"></i></span>' +
				'<div><div class="dlt-stat-num">' + num + '</div>' +
				'<div class="dlt-stat-cap">' + cap + '</div></div></div>';
		}

		function mixBlock(model) {
			var keys = Object.keys(model.plans);
			if (!keys.length) return "";
			var total = model.count;
			var fills = "", legend = "";
			keys.forEach(function (k, i) {
				var n = model.plans[k];
				var pct = total ? (n / total) * 100 : 0;
				var c = "c" + (i % MIX_COLORS);
				fills += '<span class="dlt-mix-fill ' + c + '" data-w="' + pct.toFixed(1) + '"></span>';
				legend += '<span class="dlt-mix-item"><i class="dlt-dot ' + c + '"></i>' +
					esc(planMeta(k === "unspecified" ? "" : k).label) +
					" · <b>" + n + "</b></span>";
			});
			return '<div class="dlt-mix">' +
				'<div class="dlt-mix-track">' + fills + '</div>' +
				'<div class="dlt-mix-legend">' + legend + '</div></div>';
		}

		function rowHTML(d, n, cur) {
			var pm = planMeta(d.payment_plan);
			var bal = toNum(d.credit_balance);
			var delay = Math.min((n - 1) * 26, 300);
			return '<tr class="dlt-row" data-idx="' + d.__i + '" style="animation-delay:' + delay + 'ms">' +
				'<td class="dlt-idx">' + String(n).padStart(2, "0") + '</td>' +
				'<td><div class="dlt-client">' +
					'<span class="dlt-avatar dlt-avatar--' + hueOf(d.client_name) + '">' + esc(initials(d.client_name)) + '</span>' +
					'<div><div class="dlt-name">' + esc(d.client_name) + genderIcon(d.client_gender) + '</div>' +
					'<div class="dlt-contact"><i class="fas fa-phone-alt"></i>' + esc(d.client_contact || "—") + '</div></div>' +
				'</div></td>' +
				'<td><span class="dlt-badge ' + pm.cls + '"><i class="fas ' + pm.icon + '"></i>' + esc(pm.label) + '</span></td>' +
				'<td class="text-right"><div class="dlt-balance' + (bal > 0 ? "" : " dlt-balance--zero") + '">' +
					money(bal) + '</div><div class="dlt-balance-sub">' + esc(cur) + '</div></td>' +
				'<td class="dlt-date dlt-col-added">' + shortDate(d.created_at) + '</td>' +
				'<td class="text-right"><div class="dlt-acts">' +
					'<button class="dlt-act" data-act="view" title="View debtor"><i class="fas fa-eye"></i></button>' +
					'<button class="dlt-act" data-act="edit" title="Edit debtor"><i class="fas fa-user-edit"></i></button>' +
				'</div></td></tr>';
		}

		function pagerHTML(page, pages) {
			if (pages <= 1) return "";
			var out = [];
			out.push('<li class="page-item' + (page === 1 ? " disabled" : "") + '">' +
				'<a class="page-link" href="#" data-page="' + (page - 1) + '" aria-label="Previous"><i class="fas fa-chevron-left"></i></a></li>');
			var keep = {};
			[1, 2, pages - 1, pages, page - 1, page, page + 1].forEach(function (p) {
				if (p >= 1 && p <= pages) keep[p] = 1;
			});
			var prev = 0;
			for (var p = 1; p <= pages; p++) {
				if (!keep[p]) continue;
				if (p - prev > 1) out.push('<li class="page-item disabled dlt-gap"><span class="page-link">…</span></li>');
				out.push('<li class="page-item' + (p === page ? " active" : "") + '">' +
					'<a class="page-link" href="#" data-page="' + p + '">' + p + '</a></li>');
				prev = p;
			}
			out.push('<li class="page-item' + (page === pages ? " disabled" : "") + '">' +
				'<a class="page-link" href="#" data-page="' + (page + 1) + '" aria-label="Next"><i class="fas fa-chevron-right"></i></a></li>');
			return out.join("");
		}

		/* ---------- shell (the HTML string) ---------- */
		function shellHTML(model, opts, rawData) {
			var cur = opts.currency;
			var now = new Date();
			var stamp = now.toLocaleDateString("en-US", { month: "short", day: "numeric" }) + ", " +
									now.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });

			var h = '<section class="dlt">';

			/* header */
			h += '<header class="dlt-head"><div>' +
				'<div class="dlt-eyebrow"><i class="fas fa-file-invoice-dollar"></i> Accounts receivable</div>' +
				'<h2 class="dlt-title">Debtors ledger</h2>' +
				'<p class="dlt-msg">' + esc(model.message || "Debtors loaded") +
				(model.ok
					? ' <span class="dlt-pill dlt-pill--ok"><i class="fas fa-check-circle"></i>ok</span>'
					: ' <span class="dlt-pill dlt-pill--bad"><i class="fas fa-exclamation-circle"></i>error</span>') +
				'</p></div>' +
				'<div><div class="dlt-total-label"><i class="fas fa-coins"></i>Total owed</div>' +
				'<div class="dlt-total-num"><span>' + esc(cur) + '</span>' + money(model.total) + '</div>' +
				'<div class="dlt-total-sub">across ' + model.count + ' account' + (model.count === 1 ? "" : "s") +
				' · ' + stamp + '</div></div></header>';

			if (!model.ok) {
				h += '<div class="dlt-warn"><i class="fas fa-exclamation-triangle"></i>' +
					'The request reported a failure' + (model.message ? " — " + esc(model.message) : "") + '.</div>';
			}

			/* no debtors at all → static empty state, still show the log */
			if (!model.count) {
				h += '<div class="dlt-empty-static"><div class="dlt-empty">' +
					'<i class="fas fa-folder-open"></i>No debtors on record.<br>Credit accounts will appear here as they are created.</div></div>';
			} else {
				/* stats band */
				var planKeys = Object.keys(model.plans);
				h += '<div class="dlt-stats">' +
					statTile("dlt-ico-teal", "fa-users", model.count, "Debtors on file") +
					statTile("dlt-ico-blue", "fa-balance-scale", cur + " " + money(model.avg), "Average balance") +
					statTile("dlt-ico-amber", "fa-angle-double-up", cur + " " + money(model.max), "Largest balance") +
					mixBlock(model) + '</div>';

				/* toolbar */
				h += '<div class="dlt-toolbar">' +
					'<div class="input-group dlt-search">' +
						'<div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-search"></i></span></div>' +
						'<input type="search" class="form-control" placeholder="Search name or contact…" data-dlt="query" autocomplete="off">' +
					'</div>' +
					'<select class="custom-select dlt-plan" data-dlt="plan" aria-label="Filter by plan">' +
					'<option value="">All payment plans</option>' +
					planKeys.map(function (k) {
						return '<option value="' + esc(k) + '">' + esc(planMeta(k === "unspecified" ? "" : k).label) +
							' (' + model.plans[k] + ')</option>';
					}).join("") +
					'</select>' +
					'<span class="dlt-count" data-dlt="count"></span></div>';

				/* table */
				h += '<div class="dlt-scroll"><table class="table dlt-table mb-0"><thead><tr>' +
					'<th style="width:52px">#</th>' +
					'<th data-dlt-sort="client_name">Client<i class="fas fa-sort dlt-sortico"></i></th>' +
					'<th>Plan</th>' +
					'<th class="text-right" data-dlt-sort="credit_balance">Balance<i class="fas fa-sort-down dlt-sortico"></i></th>' +
					'<th class="dlt-col-added" data-dlt-sort="created_at">Added<i class="fas fa-sort dlt-sortico"></i></th>' +
					'<th class="text-right">Actions</th>' +
					'</tr></thead><tbody data-dlt="rows"></tbody></table></div>';

				/* footer */
				h += '<div class="dlt-foot"><span class="dlt-showing" data-dlt="showing"></span>' +
					'<nav aria-label="Debtors pages"><ul class="pagination dlt-pager" data-dlt="pager"></ul></nav></div>';
			}

			/* run log console */
			if (model.runlog.length) {
				h += '<div class="dlt-log">' +
					'<button type="button" class="dlt-log-toggle" data-dlt="logtoggle" aria-expanded="false">' +
					'<i class="fas fa-terminal"></i> Run log <span class="dlt-log-n">' + model.runlog.length + '</span>' +
					'<i class="fas fa-chevron-down dlt-chev"></i></button>' +
					'<div class="dlt-log-body" data-dlt="logbody" hidden>' +
					model.runlog.map(function (line, i) {
						var last = i === model.runlog.length - 1;
						return '<div class="dlt-log-line' + (last ? " dlt-log-line--last" : "") +
							'" style="animation-delay:' + (i * 90) + 'ms">' +
							'<span class="dlt-log-i">' + String(i + 1).padStart(2, "0") + '</span>' +
							'<span class="dlt-log-caret">›</span><span>' + esc(line) + '</span></div>';
					}).join("") +
					'</div></div>';
			}

			/* data carrier so a rendered string can self-activate */
			h += '<scr' + 'ipt type="application/json" data-dlt="payload">' +
				JSON.stringify(rawData).replace(/</g, "\\u003c") +
				'</scr' + 'ipt>';

			return h + '</section>';
		}

		/* ---------- live behaviour ---------- */
		function paint(inst) {
			var m = inst.model, o = inst.opts;
			var list = m.debtors.filter(function (d) {
				if (inst.plan && (d.payment_plan || "") !== inst.plan) return false;
				if (inst.query) {
					var hay = ((d.client_name || "") + " " + (d.client_contact || "")).toLowerCase();
					if (hay.indexOf(inst.query) === -1) return false;
				}
				return true;
			});

			var k = inst.sortKey, dir = inst.sortDir;
			list.sort(function (a, b) {
				if (k === "credit_balance") return (toNum(a.credit_balance) - toNum(b.credit_balance)) * dir;
				if (k === "created_at") return (+new Date(a.created_at || 0) - +new Date(b.created_at || 0)) * dir;
				return String(a.client_name || "").toLowerCase().localeCompare(String(b.client_name || "").toLowerCase()) * dir;
			});

			var total = list.length;
			var pages = Math.max(1, Math.ceil(total / o.pageSize));
			if (inst.page > pages) inst.page = pages;
			var start = (inst.page - 1) * o.pageSize;
			var slice = list.slice(start, start + o.pageSize);

			var rows = inst.root.querySelector('[data-dlt="rows"]');
			rows.innerHTML = slice.length
				? slice.map(function (d, i) { return rowHTML(d, start + i + 1, o.currency); }).join("")
				: '<tr><td colspan="6"><div class="dlt-empty"><i class="fas fa-search"></i>' +
					'No debtors match your filters.</div></td></tr>';

			inst.root.querySelector('[data-dlt="count"]').textContent = total + " of " + m.count + " shown";
			inst.root.querySelector('[data-dlt="showing"]').textContent = total
				? "showing " + (start + 1) + "–" + (start + slice.length) + " of " + total
				: "no matches";
			inst.root.querySelector('[data-dlt="pager"]').innerHTML = pagerHTML(inst.page, pages);

			inst.root.querySelectorAll("[data-dlt-sort]").forEach(function (th) {
				var key = th.getAttribute("data-dlt-sort");
				var ic = th.querySelector(".dlt-sortico");
				if (key === inst.sortKey) {
					th.classList.add("dlt-sorted");
					ic.className = "fas dlt-sortico " + (inst.sortDir > 0 ? "fa-sort-up" : "fa-sort-down");
				} else {
					th.classList.remove("dlt-sorted");
					ic.className = "fas dlt-sortico fa-sort";
				}
			});
		}

		function bind(inst) {
			var root = inst.root;
			var onQuery = debounce(function (v) {
				inst.query = v.trim().toLowerCase();
				inst.page = 1;
				paint(inst);
			}, 130);

			root.addEventListener("input", function (e) {
				if (e.target.matches('[data-dlt="query"]')) onQuery(e.target.value);
			});

			root.addEventListener("change", function (e) {
				if (e.target.matches('[data-dlt="plan"]')) {
					inst.plan = e.target.value;
					inst.page = 1;
					paint(inst);
				}
			});

			root.addEventListener("click", function (e) {
				var sortTh = e.target.closest("[data-dlt-sort]");
				if (sortTh) {
					var key = sortTh.getAttribute("data-dlt-sort");
					if (inst.sortKey === key) inst.sortDir *= -1;
					else { inst.sortKey = key; inst.sortDir = key === "client_name" ? 1 : -1; }
					paint(inst);
					return;
				}

				var pg = e.target.closest("[data-page]");
				if (pg) {
					e.preventDefault();
					var p = +pg.getAttribute("data-page");
					if (p >= 1) { inst.page = p; paint(inst); }
					return;
				}

				var tg = e.target.closest('[data-dlt="logtoggle"]');
				if (tg) {
					var log = tg.closest(".dlt-log");
					var body = log.querySelector('[data-dlt="logbody"]');
					var open = log.classList.toggle("open");
					body.hidden = !open;
					tg.setAttribute("aria-expanded", open);
					return;
				}

				var act = e.target.closest(".dlt-act");
				if (act && inst.opts.onAction) {
					var tr = act.closest("tr");
					var debtor = inst.model.debtors[+tr.getAttribute("data-idx")];
					inst.opts.onAction(act.getAttribute("data-act"), debtor, tr);
				}
			});
		}

		function mount(el, data, opts) {
			var root = typeof el === "string" ? document.querySelector(el) : el;
			if (!root) return null;
			opts = Object.assign({}, DEFAULTS, opts || {});
			var model = buildModel(data);
			root.innerHTML = shellHTML(model, opts, data);

			var inst = {
				root: root, model: model, opts: opts,
				query: "", plan: "",
				sortKey: "credit_balance", sortDir: -1,  // biggest debts first
				page: 1
			};
			bind(inst);
			if (model.count) paint(inst);

			/* animate the plan-mix bar once the shell is in the DOM */
			requestAnimationFrame(function () {
				root.querySelectorAll(".dlt-mix-fill").forEach(function (f) {
					f.style.width = f.getAttribute("data-w") + "%";
				});
			});
			return inst;
		}

		/* ---------- public API ---------- */
		function renderDebtors(data, opts) {
			opts = Object.assign({}, DEFAULTS, opts || {});
			return shellHTML(buildModel(data), opts, data);
		}

		function activate(el, opts) {
			var root = typeof el === "string" ? document.querySelector(el) : el;
			var tag = root && root.querySelector('[data-dlt="payload"]');
			if (!tag) return null;
			var data;
			try { data = JSON.parse(tag.textContent); } catch (err) { data = {}; }
			return mount(root, data, opts);
		}

		window['renderDebtors'] = renderDebtors;
		window['DebtorsUI'] = { render: renderDebtors, mount: mount, activate: activate };
	})();
</script>
