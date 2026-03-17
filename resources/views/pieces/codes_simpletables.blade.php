<script>
	window['render_simpleTable'] = (dta) => {
		let dft = {
			data: {},
			cols: [],
			tablename: 'items_table',
			caption: 'items',
			viewcall: 'view_record',
			rowprops: 'data-genrecord'
		};

		dta = {...dft,...dta};

		// alert_info(`showing ${dta.caption}`,12);

		let data = dta.data;
		let cols = dta.cols;
		let tablename = dta.tablename;
		let sel = `[data-role="${tablename}"]`;
		let table = document.querySelector(sel);

		if(table !== null){
			let thead = table.querySelector(`[data-subrole="tableHead"]`);
			let tbody = table.querySelector(`[data-subrole="tableBody"]`);

			outht = '';

			cols.forEach(h => {
				outht += `<th>${h.cap}</th>`;
			});

			thead.innerHTML = outht;
			outht = '';

			data.forEach((s,id) => {
				outht += `<tr style="cursor:pointer;" data-runme="${dta.viewcall}" data-myid="${id}" ${dta.rowprops}>`;

				cols.forEach(c => {
					let f = s[c.field];
					let dta = c.show == undefined ? f : c.show(f,s,id);
					outht += `<td>${dta}</td>`;
				})
				outht += '</tr>';
			})

			if(data.length == 0){
				let l = cols.length == 0 ? 1 : cols.length;
				outht = `
					<td colspan="${l}" class="spacy-md w3-center">
						<i>no ${dta.caption} found</i>
					</td>
				`;
			}

			tbody.innerHTML = outht;

			let n = data.length;
			let myreport = `${dta.tablename}_report`;
			let rpt = document.querySelector(`#${myreport}`);
			let reportht = `showing <b class="themetxt">${n}</b> ${plural('result',n)}`;

			if(rpt == undefined){
				let summary = document.createElement('div');
				summary.className = "spacy-sm border-top border-secondary";
				summary.innerHTML = reportht;
				summary.id = myreport;

				document.querySelector(sel).parentElement.appendChild(summary);
			} else {
				rpt.innerHTML = reportht;
			}
		} else {
			alert_danger('no table found',12);
		}
	}
</script>
