<style>
	.samples_holder{
		display: flex;
		flex-wrap: wrap;
		gap: 12px;
		flex-direction: row;
		padding: 12px;
		align-items: center;
		justify-content: flex-start;
	}
	.samples_holder .samplebox{
		flex: 1 0 235px;
		max-width: 300px;
		/*display: flex;*/
		/*box-shadow: 0 0 12px rgba(122, 122, 122, 0.3);*/
		border-radius: var(--roundness);
		padding: 12px;
		position: relative;
	}
	.samples_holder .samplebox b{
		font-size: 1.6rem;
		display: block;
		color: var(--themecolor);
	}
	.samples_holder .samplebox span{
		font-size: 1.8rem;
	}
	.samples_holder .samplebox .info{
		padding: 8px 0;
	}
	.samples_holder .samplebox .actions{
		padding: 8px 0;
	}

	.previewCards_holder{
		display: flex;
	}
	.previewCards_holder .previewCard{
		flex: 1 0 235px;
		max-width: 300px;
		padding: 12px;
	}
	.previewCards_holder .previewCard img{
		height: 240px;
		width: 100%;
		object-fit: cover;
		border: 1px solid lightgray;
	}
</style>

<div class="container-fluid">
	<div class="page-header">
		<div class="page-title">
			<span class="h4">Business Data</span>
			<p>here you can view and edit business based information</p>
		</div>
		<div class="header-actions">
			<button class="mybtn secondary" onclick="refreshData()">
				<i class="fas fa-sync-alt"></i>
				Refresh
			</button>
		</div>
	</div>

	<div class="panelbg themeround spacy-sm mb-4">
		<div class="border-bottom">
			<span class="h5">Sample types and Charges</span>
			<p class="text-muted">
				heres a list of sample types that will be processed and the charges for each
			</p>
		</div>

		<div>
			<div class="samples_holder" id="sampleDataContainer">
				<div class="samplebox border">
					<div class="info">
						<b>Carbon</b>
						<span>2,000</span>
					</div>
					<div class="actions">
						<button class="mybtn secondary sm"><i class="fa fa-edit"></i> edit</button>
						<button class="mybtn bg-danger w3-text-white sm"><i class="fa fa-trash"></i> remove</button>
					</div>
				</div>
			</div>

			<div class="spacy-sm mt-3 flowline overflow gap-sm left">
				<button type="button" class="mybtn secondary" data-runme="add_sample">
					<i class="fa fa-plus mr-2"></i>Add Sample Type
				</button>
				<button type="button" class="mybtn bg-success w3-text-white" id="save_samples_btn" data-runme="save_changes" data-section="samples">
					<i class="fa fa-save mr-2"></i>Save samples info
				</button>
			</div>
		</div>
	</div>

	<div class="panelbg themeround spacy-sm mb-4">
		<div class="border-bottom">
			<span class="h5">Report Images</span>
			<p class="text-muted">
				Heres a list of images required to generate reports
			</p>
		</div>

		<div>
			<div class="row spacy-sm gap-tn previewCards_holder">
				<div class="previewCard">
					<div>
						<span class="h5 text-muted w3-center">Company Logo</span>
					</div>
					<div class="preview">
						<img src="" id="_img">
					</div>
					<div class="actions mb-2 mt-2">
						<button class="themeround btn btn-outline-secondary btn-block">change</button>
					</div>
				</div>
				<div class="previewCard">
					<div>
						<span class="h5 text-muted w3-center">Technician signature</span>
					</div>
					<div class="preview">
						<img src="" id="_img">
					</div>
					<div class="actions mb-2 mt-2">
						<button class="themeround btn btn-outline-secondary btn-block">change</button>
					</div>
				</div>
				<div class="previewCard">
					<div>
						<span class="h5 text-muted w3-center">supervisor Image</span>
					</div>
					<div class="preview">
						<img src="" id="_img">
					</div>
					<div class="actions mb-2 mt-2">
						<button class="themeround btn btn-outline-secondary btn-block">change</button>
					</div>
				</div>
				<div class="previewCard">
					<div>
						<span class="h5 text-muted w3-center">Official Stamp</span>
					</div>
					<div class="preview">
						<img src="" id="_img">
					</div>
					<div class="actions mb-2 mt-2">
						<button class="themeround btn btn-outline-secondary btn-block">change</button>
					</div>
				</div>
			</div>
		</div>

		<div class="spacy-sm mt-3 flowline overflow gap-sm left">
			<button type="button" class="mybtn bg-success w3-text-white" id="save_images_btn" data-runme="save_changes" data-section="images">
				<i class="fa fa-save mr-2"></i>Save business info
			</button>
		</div>
	</div>

	<div class="panelbg themeround spacy-sm mb-4">
		<div class="border-bottom mb-4">
			<span class="h5">Deviation Summary</span>
			<p class="text-muted">
				This is a summary of all activities done to come up with the test results
			</p>
		</div>

		<div class="spacy-sm">
			<div class="table-responsive themeround border">
				<table class="table-modern">
					<thead>
						<th>Sample type</th>
						<th>Analytical method</th>
						<th>Tolerance limit</th>
						<th style="width: 240px;">Action</th>
					</thead>
					<tbody>
						<tr>
							<td>Soil and rocks</td>
							<td>Aqua Regia digest, AAS finish</td>
							<td>Au +- 0.2ppm</td>
							<td>
								<button class="mybtn secondary sm"><i class="fa fa-edit"></i> edit</button>
								<button class="mybtn bg-danger w3-text-white sm"><i class="fa fa-trash"></i> remove</button>
							</td>
						</tr>
						<tr>
							<td>Soil and rocks</td>
							<td>Aqua Regia digest, AAS finish</td>
							<td>Au +- 0.2ppm</td>
							<td>
								<button class="mybtn secondary sm"><i class="fa fa-edit"></i> edit</button>
								<button class="mybtn bg-danger w3-text-white sm"><i class="fa fa-trash"></i> remove</button>
							</td>
						</tr>
						<tr>
							<td>Soil and rocks</td>
							<td>Aqua Regia digest, AAS finish</td>
							<td>Au +- 0.2ppm</td>
							<td>
								<button class="mybtn secondary sm"><i class="fa fa-edit"></i> edit</button>
								<button class="mybtn bg-danger w3-text-white sm"><i class="fa fa-trash"></i> remove</button>
							</td>
						</tr>
						<tr>
							<td>Soil and rocks</td>
							<td>Aqua Regia digest, AAS finish</td>
							<td>Au +- 0.2ppm</td>
							<td>
								<button class="mybtn secondary sm"><i class="fa fa-edit"></i> edit</button>
								<button class="mybtn bg-danger w3-text-white sm"><i class="fa fa-trash"></i> remove</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="spacy-sm mt-3 flowline overflow gap-sm left">
			<button type="button" class="mybtn secondary" data-runme="add_deviation_entry">
					<i class="fa fa-plus mr-2"></i>Add Entry
				</button>
			<button type="button" class="mybtn bg-success w3-text-white" id="save_deviation_btn" data-runme="save_changes" data-section="deviation">
				<i class="fa fa-save mr-2"></i>Save deviation summary
			</button>
		</div>
	</div>

	<div class="panelbg themeround spacy-sm mb-4">
		<div class="border-bottom mb-4">
			<span class="h5">Operations</span>
			<p class="text-muted">
				Here you can perform operations regarding the data you have changed
			</p>
		</div>
		<div class="flowline spread">
			<button type="button" class="mybtn bg-success w3-text-white" id="save_deviation_btn" data-runme="save_changes">
				<i class="fa fa-save mr-2"></i>Save all data
			</button>
		</div>
	</div>
</div>

<div class="modals">
	<button class="mybtn primary w3-hide" id="add_edit_samples_trig" data-toggle="modal" data-target="#add_edit_samples_modal">
		<i class="fas fa-plus"></i>
		Add sample type
	</button>

	<div class="modal fade" id="add_edit_samples_modal" tabindex="-1" role="dialog" aria-labelledby="add_edit_samples_title" aria-hidden="true" style="z-index: 102;">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content themeround borderless">
				<div class="modal-header">
					<span class="h3 modal-title" id="add_edit_samples_title">Edit sample</span>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<form id="add_payment_form" action="defined:add_payment" data-onsubmit="add_edit_sample" data-blockdefault="yes" method="post">
						<input type="hidden" name="theid" value="">
						<div class="input-group-custom">
							<div class="form-group">
								<label class="form-label" for="sample_name">Sample Name</label>
								<input type="text" class="form-control-custom" id="sample_name" name="sample_name" placeholder="the name of the sample type" required>
							</div>
							<div class="form-group">
								<label class="form-label" for="amount">Price</label>
								<input type="number" class="form-control-custom" id="amount" name="amount" placeholder="how much will you charge" required>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="mybtn secondary" data-dismiss="modal">Cancel</button>
					<button type="button" class="mybtn primary" data-submitme="#add_payment_form">Add sample type</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	window['temp_bizdata'] = {
		...bizdata,
		temp: {},
	};

	window['runOnAwake'] = () => {
		initbizstuff();
	}

	window['runOnLeave'] = () => {
		let killthese = [
			'temp_bizdata',
			'rerender_stuff',
			'add_sample',
			'save_changes',
			'add_edit_sample',
			'save_samples',
		];

		killghosts(killthese);
		// bizdata = normalizeBizdata(temp_bizdata.temp)
	}

	window['initbizstuff'] = () => {
		// load bizdata from server
		const final_step = () => {
			rerender_stuff();
		}

		final_step();
	}

	// render stuff
	window['rerender_stuff'] = () => {
		alert_dark('rendering the data');

		// isolate data
		let s_data = temp_bizdata.temp.samplesInfo || [];
		let sample_holder = sampleDataContainer;
		let outht = '';

		sample_holder.innerHTML = outht;

		s_data.forEach((s,n) => {
			outht += `
				<div class="samplebox border">
					<div class="info">
						<b>${s.type}</b>
						<span>${s.price.toLocaleString()}</span>
					</div>
					<div class="actions">
						<button class="mybtn secondary sm" data-runme="edit_sample" data-myid="${n}"><i class="fa fa-edit"></i> edit</button>
						<button class="mybtn bg-danger w3-text-white sm" data-runme="remove_sample" data-myid="${n}"><i class="fa fa-trash"></i> remove</button>
					</div>
				</div>
			`;
		});

		if(s_data.length == 0){
			outht = `
				<div class="spacy-sm w3-center">
					<i>no samples found</i>
				</div>
			`;
		}

		sample_holder.innerHTML = outht;

		refreshUI(300);
	}

	// operations
	window['save_changes'] = (el) => {
		if(el == undefined){
			alert_danger('invalid method call!');
			return;
		}

		let who = el.dataset.section || undefined;

		if(who !== undefined){
			if(typeof window[`save_${who}`] == 'function'){
				window[`save_${who}`]();
				return;
			}
		}
	}

	// sample ops
		window['add_sample'] = () => {
			add_edit_samples_trig.click();
			add_edit_samples_title.innerHTML = "add Sample type";
		}
		window['add_edit_sample'] = (el) => {
			let myid = el.theid.value;
			let s_type = el.sample_name.value;
			let s_amt = Number(el.amount.value);

			let s_obj = {
				"type" : s_type,
				"price" : s_amt,
			}

			let s_data = temp_bizdata.temp.samplesInfo;
			let dta = s_data == undefined ? [] : (s_data.length == 0 ? [] : s_data);

			console.log(s_obj);

			const is_unique = () => {
				let verif = [...dta].filter(d => {return d.type.toString().toLowerCase() == s_obj.type.toString().toLowerCase();});
				return verif.length == 0;
			}

			const step_addnew = () => {
				if(!is_unique()){
					alert_danger('that sample type already exists');
					return;
				}

				dta.push(s_obj);
				temp_bizdata.temp.samplesInfo = dta;

				final_step();
				alert_dark('sample type successfully');
			}
			const step_editone = () => {
				alert_dark('editing another');

				final_step();
			}

			const final_step = () => {
				el.reset();
				add_edit_samples_trig.click();
				window['rerender_stuff']();
			}

			if(myid == ""){
				step_addnew();
			} else {
				step_editone();
			}
		}
		window['edit_sample'] = (el) => {
			if(el == undefined){alert_danger('invalid edit_sample call');return;}

			let mid = el.dataset.myid || -1;
			if(mid >= 0){alert_danger('invalid index');return;}

			// run ops
		};
		window['remove_sample'] = (el) => {
			if(el == undefined){alert_danger('invalid remove_sample call');return;}

			let mid = el.dataset.myid || -1;
			if(mid >= 0){alert_danger('invalid index');return;}

			// run ops
		};
		window['processSamples'] = (dta,israw = false) => {
			if(dta == undefined){
				return [];
			}

			if(israw){
				alert_dark('processing to database format');

				let outlist = [];

				dta.forEach(s => {
					let obj = {};

					obj[s.type] = s.price;
					outlist.push(obj);
				});

				return outlist;
			} else {
				alert_dark('processing to tempedit format');
			}
		}
		window['save_samples'] = (skipauth,pw) => {
			let raw = temp_bizdata.temp ? temp_bizdata.temp.samplesInfo : [];

			let tosave = processSamples(raw,true);

			if(tosave.length == 0){
				alert_danger('data to be saved cant be empty!',5);
				return;
			}

			const step1 = (dta) => {
				let pushdata = {
					'itemname' : 'pricing_data',
					'itemvalue' : tosave,
				}
				topush = {...pushdata,...dta};

				window[fetch_bypass](linkAtlas['update_bizdata'],topush,'POST').then((w) => {
					responseHandler(w,final_step,w);
				})
			};

			const final_step = (dta) => {
				alert_info('biz data retrieved, rerendering...');
			}

			if(!skipauth){
				awaitConfirmPass(step1);
			} else {
				alert_info('skipping password confirmation for [\'save_samples\']');

				if(pw !== undefined){
					if(pw.admin_password !== undefined){
						step1(pw);
						return;
					}
				}

				alert_danger('invalid password confirmation bypass');
			}
		}
</script>
