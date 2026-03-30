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
				sample_id : "Markle 1",
				weight: null,
				au_reading: null,
				cu_reading: null,
				remarks: "",
			}
		],
		readings: [],
		sample_count: 3,
		has_readings: false,
		is_published: true,
	};
	let readings_divider = 'RPT';

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
						<th style="width: 48px;">final</th>
						<th style="width: 48px;"><span>no</span></th>
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
			let entries = cur_sheet.sample_data;
			let entry = entries[x];
			let edit_sample = !(cur_sheet.has_readings);
			let editht = edit_sample ?
				`<div>
					<input type="text" class="form-control-custom" data-subrole="sample_id" name="sample_id_${x}" data-myid="${x}" placeholder="sample ${n}" value="${entry.sample_id}" required>
				</div>` :
				`<div>
					<span>${entry.sample_id}</span>
				</div>`
			;

			return `
				<tr>
					<td>${n}</td>
					<td>
						${editht}
					</td>
				</tr>
			`;
		};
		const _mekrow_readings = (x,prefix='',_data,edit_sample = false,is_final = false,show_radio=true) => {
			// let prtxt = prefix == '' ? '' : `<b></b>`;
			let n = typeof x == 'number' ? x + 1 : x;
			let _dft = {
				sample_id : "sample "+x,
				weight: null,
				au_reading: null,
				cu_reading: null,
				remarks: "",
			};
			let _sid = _data.sample_id;
			let givenid = _sid.startsWith(readings_divider) ? _sid.substring(readings_divider.length).trim() : _data.sample_id;

			_data = {..._dft,..._data};

			if(cur_sheet.has_readings){
				is_final = _data.is_final;
			}

			let editht = edit_sample ?
				`<div class="w3-hide">
					<input type="text" class="form-control-custom" data-subrole="sample_id" name="sample_id_${x}" data-myid="${x}" placeholder="sample ${x + 1}" value="${_data.sample_id}" required>
				</div>` :
				`<div>
					<span data-subrole="sample_id"><b>${prefix}</b> ${_data.sample_id}</span>
				</div>`
			;
			let toggle_radio = show_radio ? `<input type="radio" class="" data-subrole="final_marker" ${is_final ? 'checked' : ''} data-myid="${x}" data-mygroup="${givenid}" name="radio_${givenid}" value="${x}">` : '';
			let show_weight = !cur_sheet.is_published ?
				`<input type="text" class="form-control-custom" data-subrole="weight" name="weight_${x}" data-myid="${x}" placeholder="weight ${n}" value="${_data.weight || ""}" required>
				` : `<span>${_data.weight || "!!"}</span>`;
			let show_au_reading = !cur_sheet.is_published ?
				`<input type="text" class="form-control-custom" data-subrole="au_reading" name="au_reading_${x}" data-myid="${x}" placeholder="au_reading ${n}" value="${_data.au_reading || ""}" required>
				` : `<span>${_data.au_reading || "!!"}</span>`;
			let show_cu_reading = !cur_sheet.is_published ?
				`<input type="text" class="form-control-custom" data-subrole="cu_reading" name="cu_reading_${x}" data-myid="${x}" placeholder="cu_reading ${n}" value="${_data.cu_reading || ""}" required>
				` : `<span>${_data.cu_reading || "!!"}</span>`;
			let show_remarks = !cur_sheet.is_published ?
				`<input type="text" class="form-control-custom" data-subrole="remarks" name="remarks_${x}" data-myid="${x}" placeholder="remarks ${n}" value="${_data.remarks || ""}">
				` : `<span>${_data.remarks || "--"}</span>`;

			return `
				<tr>
					<td>
						${toggle_radio}
					</td>
					<td>${n}</td>
					<td>
						${editht}
					</td>
					<td>
						<div>
							${show_weight}
						</div>
					</td>
					<td>
						<div>
							${show_au_reading}
						</div>
					</td>
					<td>
						<div>
							${show_cu_reading}
						</div>
					</td>
					<td>
						<div>
							${show_remarks}
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
			let count = 0;

			if(w_data.has_readings){
				w_data.readings.forEach((r,id) => {
					outht += _mekrow_readings(id,'',r,false,true);
				})
			}
			else {
				for(let x = 0;x < amt;x++){
					let n = x + 1;
					let rpt = 0;
					let mydata = w_data.sample_data[x];

					console.log('count: ',count);
					outht += _mekrow_readings(count,'',mydata,false,true);

					if(cur_sheet.sample_type.toLowerCase() == 'soil' || cur_sheet.sample_type.toLowerCase() == 'rock'){
						rpt = 2;
					} else if(cur_sheet.sample_type.toLowerCase() == 'carbon' || cur_sheet.sample_type.toLowerCase() == 'carbon activity'){
						rpt = 3;
					}

					for(let c = 1; c < rpt;c++){
						count += 1;
						console.log('count: ',count);
						outht += _mekrow_readings(count,readings_divider,mydata,false);
					}

					count += 1;
				}

				let myref = sample_ref_atlas[w_data.sample_type.toLowerCase()];

				if(myref !== null){
					outht += _mekrow_readings('REF','',{sample_id: myref},false,false,false);
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
	window['save_worksheet_readings'] = (el) => {
		let tbody = el.querySelector('[data-subrole="tableBody"]');
		let rows = tbody.querySelectorAll('tr');
		let send_pack = {};
		let readings = [];
		let s_template = {
			sample_id : "Sample n",
			weight: null,
			au_reading: null,
			cu_reading: null,
			remarks: "",
		};

		const step1 = () => {
			let readings = [];
			// package the data for sending
			rows.forEach((r,id) => {
				/*
					<tr>
						<td>
							<input type="radio" class="" data-subrole="final_marker" checked="" data-myid="1" data-mygroup="Markle 0" name="radio_Markle 0" value="1">
						</td>
						<td>2</td>
						<td>
							<div>
						<span><b></b> Markle 0</span>
					</div>
						</td>
						<td>
							<div>
								<input type="text" class="form-control-custom" data-subrole="weight" name="weight_1" data-myid="1" placeholder="weight 2" value="" required="">
							</div>
						</td>
						<td>
							<div>
								<input type="text" class="form-control-custom" data-subrole="au_reading" name="au_reading_1" data-myid="1" placeholder="au_reading 2" value="" required="">
							</div>
						</td>
						<td>
							<div>
								<input type="text" class="form-control-custom" data-subrole="cu_reading" name="cu_reading_1" data-myid="1" placeholder="cu_reading 2" value="" required="">
							</div>
						</td>
						<td>
							<div>
								<input type="text" class="form-control-custom" data-subrole="remarks" name="remarks_1" data-myid="1" placeholder="remarks 2" value="">
							</div>
						</td>
					</tr>
				*/

				let marker = r.querySelector('[data-subrole="final_marker"]').checked;
				let weight = r.querySelector('[data-subrole="weight"]').value;
				let au_reading = r.querySelector('[data-subrole="au_reading"]').value;
				let cu_reading = r.querySelector('[data-subrole="cu_reading"]').value;
				let remarks = r.querySelector('[data-subrole="remarks"]').value;
				let sample_id = r.querySelector('[data-subrole="sample_id"]').textContent;
				let val = r.querySelector('[data-subrole="final_marker"]').dataset.myid || -1;

				if(val == -1){
					alert_danger(`invalid value for entry ${id}`);
					return;
				}

				let rmks = remarks == '' ? remarks : null;
				let outdata = {
					is_final: marker,
					sample_id: sample_id,
					weight: weight,
					au_reading: au_reading,
					cu_reading: cu_reading,
					remarks: rmks,
				};

				// let val = npt.value;
				let tmp = {
					...s_template,
					...outdata,
				};

				readings.push(tmp);
			})

			send_pack.sample_id = cur_rec_sample_id;
			send_pack.readings = readings;

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

		// alert_info('savin readings');
		step1();
	}
</script>
