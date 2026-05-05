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
	window['myaddition'] = [
		'mekmyTable',
		'save_worksheet_sampleids',
		'save_worksheet_readings',
	];
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
		const _mekrow_titles = (is_readings = false,is_special = false, stype = '') => {
			stype = stype.toLowerCase();

			let myht = '';

			if(is_readings){
				if(is_special){
					myht = `
						<tr class="text-uppercase">
							<th style="width: 48px;">final</th>
							<th style="width: 48px;"><span>no</span></th>
							<th><span>sample id</span></th>
							<th style="width: var(--w);"><span>initial reading</span></th>
							<th style="width: var(--w);"><span>Sample reading</span></th>
							<th style="width: var(--w);"><span>Virgin reading</span></th>
							<th style="width: var(--w_m);"><span>Remarks</span></th>
						</tr>
					`;
				} else {
					myht = `
						<tr class="text-uppercase">
							<th style="width: 48px;">final</th>
							<th style="width: 48px;"><span>no</span></th>
							<th><span>sample id</span></th>
							<th style="width: var(--w);" class="xtra ${stype == 'solution' ? 'w3-hide' : ''}"><span>weight (g)</span></th>
							<th style="width: var(--w);"><span>Au Reading</span></th>
							<th style="width: var(--w);"><span>Cu Reading</span></th>
							<th style="width: var(--w_m);"><span>Remarks</span></th>
						</tr>
					`;
				}
			} else {
				myht = `
					<th style="width: var(--w_sm);"><span>no</span></th>
					<th><span>sample id</span></th>
				`;
			}

			return myht;
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
		const _mekrow_readings = (x,prefix='',_data,edit_sample = false,is_final = false,show_radio=true,is_reference = false) => {
			// let prtxt = prefix == '' ? '' : `<b></b>`;
			let n = typeof x == 'number' ? x + 1 : x;
			let _dft = {
				sample_id : "sample "+x,
				weight: null,
				au_reading: null,
				cu_reading: null,
				is_reference: false,
				remarks: "",
			};
			let refname = sample_ref_atlas[cur_sheet.sample_type.toLowerCase()];
			let _sid = _data.sample_id == undefined ? (refname == undefined ? '??' : refname) : _data.sample_id;
			let givenid = _sid.startsWith(readings_divider) ? _sid.substring(readings_divider.length).trim() : _data.sample_id;

			_data = {..._dft,..._data};
			console.log('current row data: ',_data)

			if(cur_sheet.has_readings){
				is_final = _data.is_final;
				show_radio = !_data.is_reference;
				n = show_radio ? n : 'REF';
				is_reference = _dft.is_reference;
			}

			let mgg = is_reference ? 'w3-text-red' : 'w3-text-orange';
			mgg = '';

			let editht = edit_sample ?
				`<div class="w3-hide">
					<input type="text" class="form-control-custom" data-subrole="sample_id" name="sample_id_${x}" data-myid="${x}" placeholder="sample ${x + 1}" value="${_data.sample_id}" required>
				</div>` :
				`<div>
					<span class="${mgg}" data-subrole="sample_id"><b>${prefix}</b> ${givenid || '??'}</span>
				</div>`
			;
			let toggle_radio = !show_radio ? `opacity: 0;` : '';
			let toggle_editable = cur_sheet.is_published ? 'pointer-events: none;' : '';
			let is_special = cur_sheet.sample_type.toLowerCase() == 'carbon activity';
			let noweight = cur_sheet.sample_type.toLowerCase() == 'solution';
			let weightval = noweight ? '--' : _data.weight == undefined ? "!!" : _data.weight;

			let show_weight = !cur_sheet.is_published ?
				`<input type="number" oninput="update_readings_preview('${x}')" step="0.01" class="form-control-custom" data-subrole="weight" name="weight_${x}" data-myid="${x}" placeholder="${is_special ? 'initial' : 'weight'} ${n}" value="${weightval == '--' ? 0.01 : weightval}" ${noweight ? '' : 'required'}>
				` : `<span>${weightval}</span>`;
			let show_au_reading = !cur_sheet.is_published ?
				`<input type="number" oninput="update_readings_preview('${x}')" step="0.01" class="form-control-custom" data-subrole="au_reading" name="au_reading_${x}" data-myid="${x}" placeholder="${is_special ? 'sample' : 'au_reading'} ${n}" value="${_data.au_reading || ""}" required>
				` : `<span>${_data.au_reading || "!!"}</span>`;
			let show_cu_reading = !cur_sheet.is_published ?
				`<input type="number" oninput="update_readings_preview('${x}')" step="0.01" class="form-control-custom" data-subrole="cu_reading" name="cu_reading_${x}" data-myid="${x}" placeholder="${is_special ? 'virgin' : 'cu_reading'} ${n}" value="${_data.cu_reading || ""}" required>
				` : `<span>${_data.cu_reading || "!!"}</span>`;
			let show_remarks = !cur_sheet.is_published ?
				`<input type="text" oninput="update_readings_preview('${x}')" class="form-control-custom" data-subrole="remarks" name="remarks_${x}" data-myid="${x}" placeholder="remarks ${n}" data-myval="${_data.remarks}" value="${_data.remarks || ""}">
				` : `<span>${_data.remarks || "--"}</span>`;

			return `
				<tr ${is_reference ? 'data-refcol' : ''} data-rowid="${x}" data-subrole="readings_entry">
					<td>
						<input type="radio" class="" data-subrole="final_marker" ${is_final ? 'checked' : ''} data-myid="${x}" data-mygroup="${givenid}" name="radio_${givenid}" value="${x}" style="${toggle_radio}${toggle_editable}">
					</td>
					<td>${n}</td>
					<td>
						${editht}
					</td>
					<td class="${noweight ? 'w3-hide' : ''}">
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
						<div class="w3-hide">
							${show_remarks}
						</div>
						<div data-subrole="results_preview" data-myid="${x}">
						</div>
					</td>
				</tr>
			`;
		}

		const renderCreate = () => {
			// let special = cur_sheet.sample_type.toLowerCase() == 'carbon activity';
			table_head.innerHTML = _mekrow_titles(false);

			// alert_dark(`sample type: ${cur_sheet.sample_type.toLowerCase()}`);

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
			let special = cur_sheet.sample_type.toLowerCase() == 'carbon activity';
			table_head.innerHTML = _mekrow_titles(true, special, cur_sheet.sample_type);

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
					} else if(cur_sheet.sample_type.toLowerCase() == 'carbon'){
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
					outht += _mekrow_readings('REF','',{sample_id: myref},false,false,false,true);
				}
			}

			table_body.innerHTML = outht;

			setTimeout(() => {
				update_readings_preview();
			}, 400);
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

				let is_ref = false;

				if(r.dataset.refcol !== undefined){
					is_ref = true;
				}
				let mymarker = r.querySelector('[data-subrole="final_marker"]');
				let marker = mymarker == undefined ? false : mymarker.checked;
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

				let rmks = remarks !== '' ? remarks : null;
				let outdata = {
					is_final: marker,
					sample_id: sample_id,
					weight: weight,
					au_reading: au_reading,
					cu_reading: cu_reading,
					remarks: rmks,
					is_reference: is_ref,
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
	window['update_readings_preview'] = (n) => {
		let myrow = document.querySelectorAll('[data-subrole="readings_entry"]');

		myrow.forEach(myrow => {
			let s_type = cur_sheet.sample_type.toLowerCase();
			let preview = myrow.querySelector('[data-subrole="results_preview"]');

			let npt_w = myrow.querySelector('input[data-subrole="weight"]');
			let npt_au_read = myrow.querySelector('input[data-subrole="au_reading"]');
			let npt_cu_read = myrow.querySelector('input[data-subrole="cu_reading"]');

			let weight = npt_w.value == '' ? 0 : Number(npt_w.value);
			let au_reading = npt_au_read.value == '' ? 0 : Number(npt_au_read.value);
			let cu_reading = npt_cu_read.value == '' ? 0 : Number(npt_cu_read.value);

			let [AU_ppm, CU_ppm, activity] = run_calculator(s_type,weight,au_reading,cu_reading);

			preview.innerHTML = s_type.includes('activity') ? `
					<div>
						<b>Activity: </b>
						<span>${activity.toFixed(2)}%</span>
					</div>
				` : `
				<div>
					<b>AU ppm: </b>
					<span>${AU_ppm.toFixed(2)}</span>
				</div>
				<div>
					<b>CU ppm: </b>
					<span>${CU_ppm.toFixed(2)}</span>
				</div>
			`;
		})
	}

	window['run_calculator'] = (typ,weight,au_reading,cu_reading) => {
		// alert_dark('running calc');
		console.log('inputs: ',`
			weight => ${weight},
			au_reading => ${au_reading},
			cu_reading => ${cu_reading},
		`);

		let au_ppm = Math.random().toFixed(2);
		let cu_ppm = Math.random().toFixed(2);
		let activity = Math.random().toFixed(2) * 95.566;

		let calc_cu_ppm = 0;
		let calc_au_ppm = 0;

		$calculate_nermal = () => {
			if(weight == 0){
				au_ppm = cu_ppm = 0;
				return;
			}
			au_ppm = (au_reading * 4 * (250 - (0.35 * weight))) / (weight * 50);
			cu_ppm = (300 / weight) * cu_reading;
		};
		$calculate_soil = () => {
			return $calculate_nermal();
		};
		$calculate_solution = () => {
			au_ppm = au_reading;
			cu_ppm = cu_reading;
		};
		$calculate_carbon = () => {
			au_ppm = au_reading * 100;
			cu_ppm = cu_reading * 100;
		};
		$calculate_carbon_activity = () => {
			let act = weight;
			let virgin = au_reading;
			let initial = cu_reading;

			let i = Number(initial);
			let v = Number(virgin);
			let a = Number(act);

			let top = (i - a).toFixed(3);
			let bottom = (i - v).toFixed(3);
			let clc = top / bottom;

			// console.log(`(i - a) => (${i} - ${a}) => ${top}`)
			// console.log(`(i - v) => (${i} - ${v}) => ${bottom}`)
			// console.log(`calc => (top / bottom) => (${top} / ${bottom}) => ${clc}`)
			// console.log(`act => (calc * 90) => (${clc} * 90) => ${clc * 85}`)

			activity = clc * 85;
		};

		let stype = typ.toLowerCase();

		switch (stype) {
			case 'soil':
				$calculate_soil();
				break;
			case 'carbon':
				$calculate_carbon();
				break;
			case 'solution':
				$calculate_solution();
				break;
			case 'carbon activity':
				// $update_runlog('processing carbon_activity');
				$calculate_carbon_activity();
				break;
			default:
				$calculate_nermal();
				break;
		}

		console.log([
			au_ppm,
			cu_ppm,
			activity,
		])

		return [
			au_ppm,
			cu_ppm,
			activity,
		];
	}

	setTimeout(() => {
		window['killOnLeave'] = window['killOnLeave'] == undefined ? [] : killOnLeave;
		killlist = killOnLeave;
		killlist = [...killlist,...myaddition];

		window['killOnLeave'] = killlist;
	},600)
</script>
