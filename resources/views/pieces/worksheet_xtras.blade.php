<script>
	window['cur_sample'] = {};
	window['cur_rec_sample_id'] = 0;
	window['cur_sheet'] = {
		id: 1,
		sample_type: 'soil',
		sample_data: [
			{
				sample_id : "John Makali",
				weight: 200,
				au_reading: 3.45,
				cu_reading: 2.34,
				remarks: "",
			},
			{
				sample_id : "Markle 0",
				weight: null,
				au_reading: null,
				cu_reading: null,
				remarks: "",
			},
			{
				sample_id : "Markle 0",
				weight: null,
				au_reading: null,
				cu_reading: null,
				remarks: "",
			}
		],
		sample_count: 3
	};

	window['mekmyTable'] = (m = 'create',sel='') => {
		let tableguy = document.querySelector(sel);

		if(tableguy == undefined){
			alert_danger('no table to render the worksheet on');
			return;
		}

		let table_head = tableguy.querySelector('[data-subrole="tableHead"]');
		let table_body = tableguy.querySelector('[data-subrole="tableBody"]');

		// utils
		const _mekrow_titles = (is_readings) => {
			return !(is_readings) ?
				`
					<th style="width: var(--w_sm);"><span>no</span></th>
					<th><span>sample id</span></th>
				` :
				`
					<tr class="text-uppercase">
						<th style="width: var(--w_sm);"><span>no</span></th>
						<th><span>sample id</span></th>
						<th style="width: var(--w);"><span>weight (g)</span></th>
						<th style="width: var(--w);"><span>Au Reading</span></th>
						<th style="width: var(--w);"><span>Cu Reading</span></th>
						<th style="width: var(--w_m);"><span>Remarks</span></th>
					</tr>
				`;
		}
		const _mekrow_entries = (x) => {
			let n = x + 1;

			return `
				<tr>
					<td>${n}</td>
					<td>
						<div>
							<input type="text" class="form-control-custom" data-subrole="sample_id" name="sample_id_${x}" data-myid="${x}" placeholder="sample ${n}" value="sample ${n}" required>
						</div>
					</td>
				</tr>
			`;
		};
		const _mekrow_readings = (x,prefix='',_data,edit_sample = false) => {
			// let prtxt = prefix == '' ? '' : `<b></b>`;
			let n = x + 1;
			let _dft = {
				sample_id : "sample "+x,
				weight: null,
				au_reading: null,
				cu_reading: null,
				remarks: "",
			};
			_data = {..._dft,..._data};

			let editht = edit_sample ?
				`<div class="w3-hide">
					<input type="text" class="form-control-custom" data-subrole="sample_id" name="sample_id_${x}" data-myid="${x}" placeholder="sample ${x + 1}" value="${_data.sample_id}" required>
				</div>` :
				`<div>
					<span><b>${prefix}</b> ${_data.sample_id}</span>
				</div>`
			;

			return `
				<tr>
					<td>${n}</td>
					<td>
						${editht}
					</td>
					<td>
						<div>
							<input type="text" class="form-control-custom" data-subrole="weight" name="weight_${x}" data-myid="${x}" placeholder="weight ${n}" value="${_data.weight || ""}" required>
						</div>
					</td>
					<td>
						<div>
							<input type="text" class="form-control-custom" data-subrole="au_reading" name="au_reading_${x}" data-myid="${x}" placeholder="au_reading ${n}" value="${_data.au_reading || ""}" required>
						</div>
					</td>
					<td>
						<div>
							<input type="text" class="form-control-custom" data-subrole="cu_reading" name="cu_reading_${x}" data-myid="${x}" placeholder="cu_reading ${n}" value="${_data.cu_reading || ""}" required>
						</div>
					</td>
					<td>
						<div>
							<input type="text" class="form-control-custom" data-subrole="remarks" name="remarks_${x}" data-myid="${x}" placeholder="remarks ${n}" value="${_data.remarks || ""}">
						</div>
					</td>
				</tr>
			`;
		}

		const renderCreate = () => {
			table_head.innerHTML = _mekrow_titles(false);

			table_body.innerHTML = ``;

			let w_data = cur_sheet;
			let amt = w_data.sample_count;
			let outht = '';

			for(let x = 0;x < amt;x++){
				outht += _mekrow_entries(x);
			}

			table_body.innerHTML = outht;
		};

		const renderEntries = () => {
			table_head.innerHTML = _mekrow_titles(true);

			table_body.innerHTML = ``;

			let w_data = cur_sheet;
			let amt = w_data.sample_count;
			let outht = '';

			for(let x = 0;x < amt;x++){
				let n = x + 1;
				let rpt = 0;
				let mydata = w_data.sample_data[x];

				outht += _mekrow_readings(x,'',mydata,false);

				if(cur_sheet.sample_type == 'soil'){
					rpt = 1;
				} else if(cur_sheet.sample_type == 'carbon'){
					rpt = 2;
				}

				for(let c = 1; c < rpt;c++){
					outht += _mekrow_readings(x + c,'RPT',mydata,false);
				}
			}

			table_body.innerHTML = outht;
		};

		if(m.toLowerCase() == 'create'){
			renderCreate();
		} else if(m.toLowerCase() == 'enter_readings') {
			renderEntries();
		}
	}
	window['save_worksheet_sampleids'] = (el) => {
		let tbody = el.querySelector('[data-subrole="tableBody"]');
		let rows = tbody.querySelectorAll('tr');
		let send_pack = {};
		let s_data = [];
		let s_template = {
			sample_id : "Sample n",
			weight: null,
			au_reading: null,
			cu_reading: null,
			remarks: "",
		};

		const step1 = () => {
			// package the data for sending
			rows.forEach((r,id) => {
				let npt = r.querySelector('[data-subrole="sample_id"]');
				let val = npt.value;
				let tmp = {
					...s_template,
					sample_id: val,
				};

				s_data.push(tmp);
			})

			send_pack.sample_id = cur_rec_sample_id;
			send_pack.s_data = s_data;

			console.log(send_pack);
			alert_silent(send_pack);
			step2();
		};

		const step2 = () => {
			sendform(el,step3,step3b,send_pack);
		};

		const step3 = (d) => {
			let dta = d.data;
			cur_sheet = dta.sendme;
			window[el.dataset.callback](dta);

			alert_success('worksheet saved successfully');
		};
		const step3b = (err) => {
			alert_danger('saving worksheet failed');
		}

		// start the process
		step1();
	}
</script>
