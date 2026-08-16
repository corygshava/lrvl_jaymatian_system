<style>
	/*
		============================================================
		Debtors Ledger UI  ·  debtors.css
		All styles scoped under .dlt — safe to inject anywhere.
		============================================================
	*/

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
		background-repeat: no-repeat;
		background-position-x: right;
		background-position-y: center;
	}

	/* ---------- table ---------- */
	.dlt-scroll {
		max-height: calc(100vh - 250px);
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
	.dlt-acts { display: flex; gap: 6px; justify-content: flex-end; transition: opacity .2s; }
	/*.dlt-row:hover .dlt-acts { opacity: 1; }*/
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

	/* end of AI slop */
	.modal_task_chip{
		border: 1px solid var(--clr-border);
		display: flex;
		flex-direction: column;
		cursor: pointer;
		position: relative;
	}
	.modal_task_chip:hover{
		border: 1px solid var(--themecolor);
	}
	.modal_task_chip .hovicon {
	    position: absolute;
	    top: 0;
	    right: 0;
	    opacity: 0;
	    /*height: 100%;*/
	    padding: 4px;
	    display: flex;
	    align-items: center;
	    translate: 0px -10px;
	    color: var(--themecolor) !important;
	}
	.modal_task_chip:hover .hovicon{
	    translate: 0 0;
		opacity: 1;
	}
	.modal_task_chip .subhead{
		letter-spacing: 2px;
		text-transform: uppercase;
		opacity: 0.8;
	}
	.modal_task_chip .theitems>div{
		flex: 1 0 80px;
		font-size: 0.8rem;
	}
</style>

<div>
	<button class="mybtn primary w3-hide" id="view_debtors_modal_trig" data-toggle="modal" data-target="#view_debtors_modal">
		<i class="fas fa-plus"></i>
		view_debts
	</button>

	<div class="modal fade" id="view_debtors_modal" tabindex="-1" role="dialog" aria-labelledby="view_debtors_modal_title" aria-hidden="true" style="z-index: 102;">
		<div class="modal-dialog modal-lg themodal" role="document" style="max-width: 1280px">
			<div class="modal-content themeround borderless">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<span class="h3 modal-title w3-hide" id="view_debtors_modal_title">All debtors</span>
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
	window['myaddition_debtors'] = [
		'renderDebtors',
		'DebtorsUI',
		'debtorsui_open_tasks_modal',
		'debtorsui_view_client_info',
		'debtorsui_make_client_invoice',
		'just_render_debtors',
		'render_the_debtors',
		'manage_this_task',
		'debtorsui_last_client_data',

		'esc',
		'toNum',
		'money',
		'shortDate',
		'debounce',
		'initials',
		'hueOf',
		'planMeta',
		'genderIcon',
		'buildModel',
		'statTile',
		'mixBlock',
		'rowHTML',
		'pagerHTML',
		'shellHTML',
		'paint',
		'bind',
		'mount',
		'renderDebtors',
		'activate',
	];
	window['debtorsui_open_tasks_modal'] = (el) => {
		console.log('last debtors data: ',debtorsui_last_client_data);

		if(el == undefined){
			alert_danger('invalid procedure call');
			return;
		}
		if(el.dataset == undefined){
			alert_danger('invalid argument');
			return;
		}
		if(el.dataset.myid == undefined){
			alert_danger('no id attached');
			return;
		}
		if(debtorsui_last_client_data == undefined){
			alert_danger('Invalid data setup');
			return;
		}

		let debtors = debtorsui_last_client_data.sendme.debtors;
		let id = el.dataset.myid;
		let f_list = debtorsui_last_client_data.sendme.debtors.filter(d => {return d.id == id});

		if(f_list.length == 0){
			alert_danger('invalid client id passed');
			return;
		}

		let cl = f_list[0];
		let tasks = cl.tasks;

		let con = `black girl magic, like wtf are these writers on 😂<br><div>`;
		con = '';
		con = tasks.length > 0 ? con : `<div>${mekStandin('this client has no tasks')}`;

		tasks.forEach(t => {
			con += `
				<div class="modal_task_chip spacy-tn mb-2 themeround" data-runme="manage_this_task" data-myid="${t.id}" data-dismiss="modal">
					<div class=head"">${mekBold(t.task_no)}</div>
					<div class="flowline overflow gap-sm theitems mt-2 border-top pt-2">
						<div>
							<small class="subhead">task details</small>
							<div>samples: ${mekBold(t.sample_count)}</div>
							<div>has discount: ${mekBold(t.cost < t.fullcost ? 'yes' : 'no', '')}</div>
						</div>
						<div>
							<small class="subhead">PAYMENT STATUS</small>
							<div>progress: ${mekBold(formatNumber(t.paid))} / ${mekBold(formatNumber(t.cost))}</div>
							<div>balance: ${mekBold(formatNumber(t.balance))}</div>
						</div>
						<div>
							<small class="subhead">Timestamps</small>
							<div>brought: ${mekBold(formatDate1(t.created_at))}</div>
							<div>last update: ${mekBold(formatDate1(t.updated_at))}</div>
						</div>
					</div>
					<div class="hovicon">
						<b class="mybadge">click to manage</b>
					</div>
				</div>
			`;
		})

		con += '</div>';

		mekModal({
			title: `${mekStrong(cl.client_name,'themetxt')}: unpaid_tasks`,
			sub: `here are the unpaid tasks from ${mekBold(cl.client_name)}`,
			content: con
		});
	}
	window['debtorsui_view_client_info'] = (el) => {
		if(el == undefined){
			alert_danger('[debtorsui_view_client_info] invalid procedure call');
			return;
		}
		if(el.dataset == undefined){
			alert_danger('[debtorsui_view_client_info] invalid argument');
			return;
		}
		if(el.dataset.myid == undefined){
			alert_danger('[debtorsui_view_client_info] no id attached');
			return;
		}

		let id = el.dataset.myid;
		window[variableAtlas['client_id']] = id;
		loadPage({template: linkAtlas['client_info'],sidenote: pageNames['client_info']});
	}
	window['debtorsui_make_client_invoice'] = (el) => {
		if(el == undefined){
			alert_danger('[debtorsui_make_client_invoice] invalid procedure call');
			return;
		}
		if(el.dataset == undefined){
			alert_danger('[debtorsui_make_client_invoice] invalid argument');
			return;
		}
		if(el.dataset.myid == undefined){
			alert_danger('[debtorsui_make_client_invoice] no id attached');
			return;
		}

		let id = el.dataset.myid;
		window[variableAtlas['client_id']] = id;
		loadPage({template: linkAtlas['mek_invoice'],sidenote: pageNames['mek_invoice']});
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
			window[fetch_bypass](linkAtlas['get_all_debtors_raw'],{t_range: Trange},'POST').then(dta => {
				if(!dta.success || !dta.result){
					throw new Error(dta.message);
				}

				responseHandler(dta, step2, dta);
			}).catch(err => {
				outht = `There was an error fetching Debtors information: <br><b class="${err.result !== undefined ? 'w3-text-orange' : 'w3-text-red'}">${err.message}</b>`;
				container.innerHTML = mekError('Error loading daily report', outht);
				report_sect_errshown = true;
				console.log(err);
			})
		}

		const step2 = (d) => {
			// render the recieved data
			// alert_info('rendering data');
			let outht = mekStandin(`preparing interface<br>total owed: <b class="themetxt">${formatNumber(d.sendme.total_owed,2)}</b>`);
			console.log(d);

			// outht = renderDebtors(d);
			const mountme = (el, d) => {
				// el.innerHTML = outht;
			}

			DebtorsUI.mount(container, d);
			// mountme(container, d);
			// container.innerHTML = outht;
		}

		step1();
	}

	window['manage_this_task'] = (el) => {
		window[variableAtlas['task_id']] = el.dataset.myid;
		loadPage({template: linkAtlas['task_info'], sidenote: pageNames['task_info']});
	}

	window['debtorsui_last_client_data'] = undefined;

	setTimeout(() => {
		window['killOnLeave'] = window['killOnLeave'] == undefined ? [] : killOnLeave;
		killlist = killOnLeave;
		killlist = [...killlist, ...myaddition_debtors];

		window['killOnLeave'] = killlist;
	},randomRange(500,800));

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

	// (function () {
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
			var debtors = Array.isArray(send.debtors) ? [...send.debtors] : [];
			debtors.forEach(function (x, i) { x.__i = i; }); // stable index for callbacks

			var sum = debtors.reduce(function (a, x) { return a + toNum(x.credit_balance); }, 0);
			var hasTotal = send.total_owed != null && send.total_owed !== "";
			var plans = {};

			debtors.forEach(function (x) {
				var k = x.payment_plan || "unspecified";
				plans[k] = (plans[k] || 0) + 1;
			});

			let balances = [...debtors].map(d => {return toNum(d.credit_balance);});
			let mymax = 0;
			balances.forEach(n => {
				if(n >= mymax){
					mymax = n;
				}
			})
			let mymin = mymax;
			balances.forEach(n => {
				if(n <= mymin){
					mymin = n;
				}
			})

			return {
				ok: d.result !== false,
				message: d.message || "",
				runlog: Array.isArray(d.runlog) ? d.runlog : [],
				debtors: debtors,
				count: debtors.length,
				total: hasTotal ? toNum(send.total_owed) : sum,
				sum: sum,
				avg: debtors.length ? sum / debtors.length : 0,
				max: mymax,
				min: mymin,
				plans: plans
			};
		}

		/* ---------- fragments ---------- */
		function statTile(icoCls, icon, num, cap) {
			return `
				<div class="dlt-stat">
					<span class="dlt-stat-ico ${icoCls}"><i class="fas ${icon}"></i></span>
					<div>
						<div class="dlt-stat-num">${num}</div>
						<div class="dlt-stat-cap">${cap}</div>
					</div>
				</div>
			`;
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
			let opentaskht = [null, undefined, 0].includes(d.unpaid_tasks_count) ? `` : `
				<button class="dlt-act" title="View debtor" data-runme="debtorsui_open_tasks_modal" data-myid="${d.id}"><i class="${iconAtlas['tasks']}"></i></button>
			`;

			return `
				<tr class="dlt-row" data-idx="${d.__i}" style="animation-delay:${delay}ms">
					<td class="dlt-idx">${String(n).padStart(2, "0")}</td>
					<td class="dlt-idx">${d.id}</td>
					<td>
						<div class="dlt-client">
							<span class="dlt-avatar dlt-avatar--${hueOf(d.client_name)}">${esc(initials(d.client_name))}</span>
						<div>
						<div class="dlt-name">${esc(d.client_name)} ${genderIcon(d.client_gender)}</div>
						<div class="dlt-contact">
							<i class="fas fa-phone-alt"></i> ${esc(d.client_contact || "—")}
						</div>
					</td>
					<td>
						<span class="dlt-badge ${pm.cls}"><i class="fas ${pm.icon}"></i>${esc(pm.label)}</span>
					</td>
					<td class="text-right">
						<div class="dlt-balance">${money(bal)}</div>
						<div class="dlt-balance-sub">${esc(cur)}</div>
					</td>
					<td>
						<div class="text-right"><b>${d.unpaid_tasks_count || '--'}</b></div>
					</td>
					<td class="dlt-date dlt-col-added flow" style="gap:2px;">
						<span>created: <b class="w3-text-grey">${shortDate(d.created_at)}</b></span>
						<span>last visit: <b class="w3-text-purple">${shortDate(d.updated_at)}</b></span>
					</td>
					<td class="text-right">
						<div class="dlt-acts is_ghost_">
							${opentaskht}
							<button class="dlt-act" title="View debtor" data-runme="debtorsui_view_client_info" data-myid="${d.id}"><i class="fas fa-info"></i></button>
							<button class="dlt-act" title="make invoice" data-runme="debtorsui_make_client_invoice" data-myid="${d.id}"><i class="${iconAtlas['invoices']}"></i></button>
						</div>
					</td>
				</tr>
			`;
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
			let cur = opts.currency;
			let now = new Date();
			let stamp = formatDate1(now, true);
			// console.log('making the shell');

			var h = '<section class="dlt">';
			let model_state_ht = model.ok
				? ' <span class="dlt-pill dlt-pill--ok"><i class="fas fa-check-circle"></i>ok</span>'
				: ' <span class="dlt-pill dlt-pill--bad"><i class="fas fa-exclamation-circle"></i>error</span>';

			/* header */
			h += `
				<header class="dlt-head">
					<div>
						<div class="dlt-eyebrow w3-hide">
							<i class="fas fa-file-invoice-dollar"></i> Accounts receivable
						</div>
						<h2 class="dlt-title">Debtors ledger</h2>
						<p class="dlt-msg w3-hide">
							${esc(model.message)} ${model_state_ht}
						</p>
					</div>
					<div>
						<div class="dlt-total-label">
							<i class="fas fa-coins"></i> Total owed
						</div>
						<div class="dlt-total-num">
							<span>${esc(cur)}</span> ${money(model.total)}
						</div>
						<div class="dlt-total-sub">
							from ${model.count} ${plural('client',model.count)} | ${stamp}
						</div>
					</div>
				</header>
			`;

			// if the op was a success
			if (!model.ok) {
				h += `
					<div class="dlt-warn">
						<i class="fas fa-exclamation-triangle"></i>
						The request reported a failure: ${(model.message ? " ... " + esc(model.message) : "")}
					</div>
				`;
			}

			if (!model.count || model.count == 0) {
				alert_info('no debtors found');

				h += `
					<div class="dlt-empty-static">
						<div class="dlt-empty">
							<i class="fas fa-folder-open"></i>
							No debtors on record.<br>Credit accounts will appear here as they are created.
						</div>
					</div>
				`;
			} else {
				// stats
				var planKeys = Object.keys(model.plans);

				h += `
					<div class="dlt-stats">
						${statTile("dlt-ico-teal", "fa-users", model.count, "Debtors on file")}
						${/*statTile("dlt-ico-blue", "fa-balance-scale", cur + " " + money(model.avg), "Average balance")*/''}
						${statTile("dlt-ico-amber", "fa-angle-double-up", cur + " " + money(model.max), "Largest balance")}
						${statTile("dlt-ico-amber", "fa-angle-double-up", cur + " " + money(model.min), "smallest balance")}
						${mixBlock(model)}
					</div>`;

				/* toolbar */
				h += `
						<div class="dlt-toolbar">
							<div class="input-group dlt-search">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="fas fa-search"></i></span>
								</div>
								<input type="search" class="form-control" placeholder="Search name or contact…" data-dlt="query" autocomplete="off">
							</div>
							<div>
								<select class="custom-select dlt-plan" data-dlt="plan" aria-label="Filter by plan">
									<option value="">All payment plans</option>${
										planKeys.map(function (k) {
											return `<option value="${esc(k)}">${esc(planMeta(k === "unspecified" ? "" : k).label)} ${model.plans[k]}</option>`;
										}).join("")
									}
								</select>
								<span class="dlt-count" data-dlt="count"></span>
							</div>
						</div>
					`;

				/* table */
				h += `
					<div class="dlt-scroll">
						<table class="table dlt-table mb-0">
							<thead>
								<tr>
									<th style="width:52px">#</th>
									<th style="width:52px">id</th>
									<th data-dlt-sort="client_name">Client <i class="fas fa-sort dlt-sortico"></i></th>
									<th data-dlt-sort="payment_plan">Payment Plan</th>
									<th class="text-right" data-dlt-sort="credit_balance">Balance <i class="fas fa-sort-down dlt-sortico"></i></th>
									<th data-dlt-sort="unpaid_tasks" style="width:52px">unpaid tasks <i class="fas fa-sort dlt-sortico"></i></th>
									<th class="dlt-col-added" data-dlt-sort="created_at">Timestamps <i class="fas fa-sort dlt-sortico"></i></th>
									<th class="text-right w3-hide_">Actions</th>
								</tr>
							</thead>
							<tbody data-dlt="rows">
							</tbody>
						</table>
					</div>
				`;

				/* footer */
				h += `
					<div class="dlt-foot">
						<span class="dlt-showing" data-dlt="showing"></span>
						<div aria-label="Debtors pages">
							<ul class="pagination dlt-pager" data-dlt="pager"></ul>
						</div>
					</div>
				`;
			}

			/* data carrier so a rendered string can self-activate */
			h += '<scr' + 'ipt type="application/json" data-dlt="payload">' +
				JSON.stringify(rawData).replace(/</g, "\\u003c") +
				'</scr' + 'ipt>';
			h += `</section>`;

			return h;
		}

		/* ---------- live behaviour ---------- */
		function paint(inst) {
			// alert_info('rendering stuff');

			var m = inst.model, o = inst.opts, outcl = [];
			// console.log('debtors before',m.debtors);
			var list = m.debtors.filter(function (d) {
				if (inst.plan && (d.payment_plan || "") !== inst.plan) return false;
				if (inst.query) {
					let sch = inst.query;

					if(sch.toLowerCase().includes('exclude:') || sch.toLowerCase().includes('isolate:')){
						let cids = sch.split(":")[1].split(',').map(n => {return Number(n);});
						let con = cids.includes(d.id);
						let f_con = sch.toLowerCase().includes('exclude:') ? con : !con;

						outcl = cids;
						if(f_con){
							// alert_info('found exclude');
							return false;
						}
					} else {
						// alert_info('not running exclude');
						var hay = ((d.client_name || "") + " " + (d.client_contact || "")).toLowerCase();
						if (hay.indexOf(inst.query) === -1) return false;
					}
				}
				return true;
			});

			// console.log('filtered',outcl);
			// console.log('debtors after',list);

			var k = inst.sortKey, dir = inst.sortDir;
			list.sort(function (a, b) {
				if (k === "credit_balance") return (toNum(a.credit_balance) - toNum(b.credit_balance)) * dir;
				if (k === "created_at") return (+new Date(a.updated_at || 0) - +new Date(b.updated_at || 0)) * dir;
				if (k === "unpaid_tasks") return (toNum(a.unpaid_tasks) - toNum(b.unpaid_tasks)) * dir;
				// if (k === "created_at") return (+new Date(a.created_at || 0) - +new Date(b.created_at || 0)) * dir;
				return String(a.client_name || "").toLowerCase().localeCompare(String(b.client_name || "").toLowerCase()) * dir;
			});

			var total = list.length;
			var pages = Math.max(1, Math.ceil(total / o.pageSize));
			if (inst.page > pages) inst.page = pages;
			var start = (inst.page - 1) * o.pageSize;
			var slice = list.slice(start, start + o.pageSize);

			var rows = inst.root.querySelector('[data-dlt="rows"]');
			rows.innerHTML = slice.length > 0
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

				if(ic == undefined){
					return;
				}

				if (key === inst.sortKey) {
					th.classList.add("dlt-sorted");
					ic.className = "fas dlt-sortico " + (inst.sortDir > 0 ? "fa-sort-up" : "fa-sort-down");
				} else {
					th.classList.remove("dlt-sorted");
					ic.className = "fas dlt-sortico fa-sort";
				}
			});

			refreshUI(100);
		}

		function bind(inst) {
			var root = inst.root;

			var onQuery = debounce(function (v) {
				inst.query = v.trim().toLowerCase();
				inst.page = 1;
				paint(inst);
			}, 130);

			root.addEventListener("input", function (e) {
				if (e.target.matches('[data-dlt="query"]')){
					onQuery(e.target.value);
				}
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
			debtorsui_last_client_data = data;

			var root = typeof el === "string" ? document.querySelector(el) : el;
			if (!root){
				alert_danger('invalid container mounted');
				return null;
			}

			opts = Object.assign({}, DEFAULTS, opts || {});

			var model = buildModel(data);
			console.log('created model',model);

			root.innerHTML = shellHTML(model, opts, data);
			console.log('generated the full UI');
			// return;

			let inst = {
				root: root,
				model: model,
				opts: opts,
				query: "",
				plan: "",
				sortKey: "credit_balance",
				sortDir: -1,				// biggest debts first
				page: 1
			};
			console.log('current instance: ', inst);
			bind(inst);
			// return;
			if (model.count){
				paint(inst);
			}

			/*
			// animate the plan-mix bar once the shell is in the DOM
			requestAnimationFrame(function () {
				return;
				root.querySelectorAll(".dlt-mix-fill").forEach(function (f) {
					f.style.width = f.getAttribute("data-w") + "%";
				});
			});

			return inst;
			// */
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
	// })();
</script>
