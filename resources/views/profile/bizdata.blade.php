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

	.info-row{
		display: flex;
		padding: 12px 0;
		gap: 12px;
		/*border-bottom: 1px solid #f3f3f3;*/
	}
	.info-row .info-label{
		font-weight: 700;
		width: 120px;
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

	<div class="flow centroid spacy-md" id="loaderbox">
		<p>loading Business information<p>
		<div class="loader"></div>
	</div>

	<div class="w3-hide slide-up" id="dataarea">
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
						<div class="preview" data-preview="company_logo">
							<img src="" id="_img">
						</div>
						<div class="actions mt-2">
							<button class="themeround btn btn-outline-secondary btn-block" data-runme="change_photo" data-which="company_logo">change</button>
						</div>
					</div>
					<div class="previewCard">
						<div>
							<span class="h5 text-muted w3-center">Technician signature</span>
						</div>
						<div class="preview" data-preview="Technician_signature">
							<img src="" id="_img">
						</div>
						<div class="actions mt-2">
							<button class="themeround btn btn-outline-secondary btn-block" data-runme="change_photo" data-which="Technician_signature">change</button>
						</div>
					</div>
					<div class="previewCard">
						<div>
							<span class="h5 text-muted w3-center">supervisor Signature</span>
						</div>
						<div class="preview" data-preview="supervisor_signature">
							<img src="" id="_img">
						</div>
						<div class="actions mt-2">
							<button class="themeround btn btn-outline-secondary btn-block" data-runme="change_photo" data-which="supervisor_signature">change</button>
						</div>
					</div>
					<div class="previewCard">
						<div>
							<span class="h5 text-muted w3-center">Official Stamp</span>
						</div>
						<div class="preview" data-preview="official_stamp">
							<img src="" id="_img">
						</div>
						<div class="actions mt-2">
							<button class="themeround btn btn-outline-secondary btn-block" data-runme="change_photo" data-which="official_stamp">change</button>
						</div>
					</div>
				</div>

				<div class="w3-hide">
					<form id="update_images_form" action="defined:update_bizdata_fyls" data-onsubmit="save_biz_images" data-callback="initbizstuff" data-blockdefault="yes" method="post">
						<input type="hidden" name="itemname" value="business_images">
						<input type="hidden" name="admin_password_mine">
						<input type="file" name="company_logo" id="company_logo" onchange="showpreview(this)" accept="image/*">
						<input type="file" name="Technician_signature" id="Technician_signature" onchange="showpreview(this)" accept="image/*">
						<input type="file" name="supervisor_signature" id="supervisor_signature" onchange="showpreview(this)" accept="image/*">
						<input type="file" name="official_stamp" id="official_stamp" onchange="showpreview(this)" accept="image/*">
					</form>
				</div>
			</div>

			<div class="spacy-sm flowline overflow gap-sm left">
				<button type="button" class="mybtn bg-success w3-text-white" id="save_images_btn" data-submitme="#update_images_form">
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
						<tbody id="deviationDataContainer">
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
				<button type="button" class="mybtn bg-success w3-text-white" id="save_deviation_btn" data-runme="save_changes" data-section="deviations">
					<i class="fa fa-save mr-2"></i>Save deviation summary
				</button>
			</div>
		</div>

		<div class="panelbg themeround spacy-sm mb-4 w3-hide">
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

		<div class="panelbg themeround spacy-sm mb-4">
			<div class="border-bottom">
				<span class="h5">Business Contacts and Location</span>
				<p class="text-muted">
					Here is information about the current branch, location and contact information
				</p>
			</div>

			<div class="spacy-sm" id="bizinfoDataDisplay">
			</div>

			<div class="spacy-sm mt-3 flowline overflow gap-sm left">
				<button type="button" class="mybtn secondary" data-runme='add_bizinfo'>
					<i class="fa fa-edit mr-2"></i>Edit
				</button>
				<button type="button" class="mybtn bg-success w3-text-white" id="save_deets_btn" data-runme="save_changes" data-section="bizinfo">
					<i class="fa fa-save mr-2"></i>Save details
				</button>
			</div>
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
					<form id="add_edit_samples_form" data-onsubmit="add_edit_sample" data-blockdefault="yes" method="post">
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
					<button type="button" class="mybtn primary" data-submitme="#add_edit_samples_form" id="add_edit_samples_submitbtn">Add sample type</button>
				</div>
			</div>
		</div>
	</div>

	<button class="mybtn primary w3-hide" id="add_edit_deviations_trig" data-toggle="modal" data-target="#add_edit_deviations_modal">
		<i class="fas fa-plus"></i>
		Add deviation summary
	</button>

	<div class="modal fade" id="add_edit_deviations_modal" tabindex="-1" role="dialog" aria-labelledby="add_edit_deviations_title" aria-hidden="true" style="z-index: 102;">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content themeround borderless">
				<div class="modal-header">
					<span class="h3 modal-title" id="add_edit_deviations_title">Edit Deviation summary entry __+__</span>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<form id="add_edit_deviations_form" action="defined:add_payment" data-onsubmit="submit_deviation_entry" data-blockdefault="yes" method="post">
						<input type="hidden" name="theid" value="">
						<div class="input-group-custom">
							<div class="form-group">
								<label class="form-label" for="sample_name">Sample type</label>
								<input type="text" class="form-control-custom" id="sample_name" name="sample_name" placeholder="the name of the sample type" required>
							</div>
							<div class="form-group">
								<label class="form-label" for="a_method">Analytical method</label>
								<input type="text" class="form-control-custom" id="a_method" name="a_method" placeholder="what method will be used" required>
							</div>
							<div class="form-group">
								<label class="form-label" for="tolerance">Tolerance limit</label>
								<input type="text" class="form-control-custom" id="tolerance" name="tolerance" placeholder="the tolerance limit of this method" required>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="mybtn secondary" data-dismiss="modal">Cancel</button>
					<button type="button" class="mybtn primary" data-submitme="#add_edit_deviations_form" id="add_edit_deviations_submitbtn">Save</button>
				</div>
			</div>
		</div>
	</div>

	<button class="mybtn primary w3-hide" id="add_edit_bizinfo_trig" data-toggle="modal" data-target="#add_edit_bizinfo_modal">
		<i class="fas fa-plus"></i>
		Add deviation summary
	</button>

	<div class="modal fade" id="add_edit_bizinfo_modal" tabindex="-1" role="dialog" aria-labelledby="add_edit_bizinfo_title" aria-hidden="true" style="z-index: 102;">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content themeround borderless">
				<div class="modal-header">
					<span class="h3 modal-title" id="add_edit_bizinfo_title">Edit business Information</span>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<form id="add_edit_bizinfo_form" data-onsubmit="submit_bizinfo" data-blockdefault="yes" method="post">
						<div class="input-group-custom">
							<div class="form-group">
								<label class="form-label" for="biz_name">Business name</label>
								<input type="text" class="form-control-custom" id="biz_name" name="biz_name" placeholder="what name will be shown on your reports" required>
							</div>
							<div class="form-group">
								<label class="form-label" for="branch_name">Branch name</label>
								<input type="text" class="form-control-custom" id="branch_name" name="branch_name" placeholder="the name of the branch" required>
							</div>
							<div class="form-group">
								<label class="form-label" for="location">Location</label>
								<input type="text" class="form-control-custom" id="location" name="location" placeholder="the location" required>
							</div>
							<div class="form-group">
								<label class="form-label" for="email">E-mail</label>
								<input type="email" class="form-control-custom" id="email" name="email" placeholder="business email" required>
							</div>
							<div class="form-group">
								<label class="form-label" for="phone">Phone</label>
								<input type="tel" class="form-control-custom" id="phone" name="phone" placeholder="the front office phone number" required>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="mybtn secondary" data-dismiss="modal">Cancel</button>
					<button type="button" class="mybtn primary" data-submitme="#add_edit_bizinfo_form" id="add_edit_bizinfo_submitbtn">Save</button>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
	/*
	require_once __DIR__.'/piece_img_processor.php';
	// */
?>

{{-- <!-- --}}
@include('pieces.bizdata_xtras');
{{-- --> --}}

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
			showmainLoader(false);
			rerender_stuff();
		}

		const step1 = () => {
			window[fetch_bypass_fyls](linkAtlas['get_bizdata'],{},'POST').then(d => {
				responseHandler(d,step2,d);
			})
		}

		const step2 = (d) => {
			populatebizdata(d.sendme);
			window['bizdata'] = parseBizdata(d.sendme);
			final_step();
		}

		showmainLoader(true);

		setTimeout(d => {
			step1();
		},300);
	}
	window['refreshData'] = () => {initbizstuff();}
	window['showmainLoader'] = (t) => {
		alert_dark(`${t ? 'showing' : 'hiding'} loader`);

		let cls = !t ? 'add' : 'remove';
		let altcls = t ? 'add' : 'remove';

		loaderbox.classList[cls]('w3-hide');
		dataarea.classList[altcls]('w3-hide');
	}

	window['populatebizdata'] = (a) => {
		// setup samples
		let data_arr = [...a].filter(d => {return d.item_name == 'pricing_data';});
		let temp = data_arr.length > 0 ? data_arr[0] : {};
		let data = parseSamples(temp);

		temp_bizdata.temp.samplesInfo = data;

		// setup photos
		data_arr = [...a].filter(d => {return d.item_name == 'business_images';});
		temp = data_arr.length > 0 ? data_arr[0] : {};
		data = temp.item_data;

		temp_bizdata.temp.bizfotos = data;

		// setup deviations
		data_arr = [...a].filter(d => {return d.item_name == 'deviation_data';});
		temp = data_arr.length > 0 ? data_arr[0] : {};
		data = temp.item_data || [];

		temp_bizdata.temp.deviationData = data;

		// setup bizinfo
		data_arr = [...a].filter(d => {return d.item_name == 'business_details';});
		temp = data_arr.length > 0 ? data_arr[0] : {};
		data = temp.item_data || undefined;

		temp_bizdata.temp.bizinfo = data;
	}

	// render stuff
		window['rerender_stuff'] = () => {
			alert_dark('rendering the data');

			// isolate and render samples data
			let s_data = temp_bizdata.temp.samplesInfo || [];
			let sample_holder = sampleDataContainer;
			let outht = '';

			sample_holder.innerHTML = outht;

			s_data.forEach((s,n) => {
				outht += `
					<div class="samplebox border slide-up" style="${mekstagger(200,n)}">
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

			// isolate and render deviation
			let d_data = temp_bizdata.temp.deviationData || [];
			let devdata_holder = deviationDataContainer;

			outht = '';
			devdata_holder.innerHTML = outht;

			d_data.forEach((d,id) => {
				outht += `
					<tr>
						<td>${d.type}</td>
						<td>${d.method}</td>
						<td>${d.tolerance}</td>
						<td>
							<button class="mybtn secondary sm" data-runme="edit_deviation" data-myid="${id}"><i class="fa fa-edit"></i> edit</button>
							<button class="mybtn bg-danger w3-text-white sm mt-2" data-runme="remove_deviation" data-myid="${id}"><i class="fa fa-trash"></i> remove</button>
						</td>
					</tr>
				`;
			});

			if(d_data.length == 0){
				outht += `
					<tr>
						<td colspan="4">
							<i>no data found</i>
						</td>
					</tr>
				`;
			}

			devdata_holder.innerHTML = outht;

			// isolate and render bizinfo
			let b_data = temp_bizdata.temp.bizinfo || {};
			bizinfoDataDisplay.innerHTML = '';

			outht = `
				<div class="info-row">
					<span class="info-label">Business name:</span>
					<span class="info-value slide-up" style="${mekstagger(200,4)}">${b_data.biz_name || '--[!]--'}</span>
				</div>
				<div class="info-row">
					<span class="info-label">Branch name:</span>
					<span class="info-value slide-up" style="${mekstagger(200,4)}">${b_data.branch_name || '--[!]--'}</span>
				</div>
				<div class="info-row">
					<span class="info-label">Location:</span>
					<span class="info-value slide-up" style="${mekstagger(200,5)}">${b_data.location || '--[!]--'}</span>
				</div>
				<div class="info-row">
					<span class="info-label">E-mail:</span>
					<span class="info-value slide-up" style="${mekstagger(200,6)}">${b_data.email || '--[!]--'}</span>
				</div>
				<div class="info-row">
					<span class="info-label">Phone:</span>
					<span class="info-value slide-up" style="${mekstagger(200,7)}">${b_data.phone || '--[!]--'}</span>
				</div>
			`;

			bizinfoDataDisplay.innerHTML = outht;

			// isolate and render images
			// path: media/bizimg/

			outht = ``;
			let photo_data = temp_bizdata.temp.bizfotos || {};

			Object.keys(photo_data).forEach(k => {
				let hol = document.querySelector(`[data-preview="${k}"]`);
				if(hol != undefined){
					let img = hol.querySelector('img');

					if(img){
						img.src = `media/bizimg/${photo_data[k]}`;
					} else {
						alert_dark(`no image found for [${k}]`);
					}
				} else {
					alert_dark(`holder for [${k}] not found`);
				}
			})

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
			add_edit_samples_submitbtn.innerHTML = "add Sample type";
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
				alert_dark('added sample type successfully');
			}
			const step_editone = () => {
				alert_dark('editing item');

				s_data[myid] = s_obj;
				final_step();
			}

			const final_step = () => {
				el.reset();
				add_edit_samples_form.theid.value = "";
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
			if(mid < 0 || mid >= temp_bizdata.temp.samplesInfo.length){alert_danger('invalid index');return;}

			let wot = temp_bizdata.temp.samplesInfo[mid];

			// run ops
			add_edit_samples_trig.click();
			add_edit_samples_title.innerHTML = "edit Sample type";
			add_edit_samples_submitbtn.innerHTML = "edit Sample type";
			add_edit_samples_form.theid.value = mid;
			add_edit_samples_form.sample_name.value = wot.type;
			add_edit_samples_form.amount.value = wot.price;
		};
		window['remove_sample'] = (el) => {
			if(el == undefined){alert_danger('invalid remove_sample call');return;}

			let mid = el.dataset.myid || -1;
			let arr = temp_bizdata.temp.samplesInfo;
			if(mid < 0 || mid >= arr.length){alert_danger('invalid index');return;}

			const deleteit = () => {
				newarr = [];

				arr.forEach((e,id) => {
					if(id == mid){return;}
					newarr.push(e);
				})
				temp_bizdata.temp.samplesInfo = newarr;
				alert_success('removed successfully');
				rerender_stuff();
			}

			// run ops
			confirmAction(mekHeading('remove sample type','h4'),`this will prevent adding any more samples of this type`,() => {
				deleteit();
			})
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
					responseHandler(w,step2,w);
				})
			};

			const step2 = (d) => {
				let sdta = parseSamples(d.sendme);
				temp_bizdata.temp.samplesInfo = sdta;
				final_step();
			}

			const final_step = (dta) => {
				alert_info('biz data retrieved, rerendering...');
				// rerender_stuff();
				initbizstuff();
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
		window['parseSamples'] = (dta) => {
			let s_data_pre = dta;
			let s_data = [];

			if(s_data_pre !== undefined){
				s_data_pre.item_data.forEach(s => {
					let key = Object.keys(s)[0];
					let val = s[key];
					let obj = {
						'type' : key,
						'price' : val,
					};

					s_data.push(obj);
				})
			}

			return s_data;
		}

	// photo ops
		window['change_photo'] = (el) => {
			if(el == undefined){
				alert_danger('invaild change_photo procedure');
				return;
			}

			let wch = el.dataset.which;

			fileInput = document.querySelector(`#${wch}`);
			change_photo_label.setAttribute('for',wch);
			add_edit_photos_trig.click();

			setTimeout(() => {
				const event = new Event('change', { bubbles: true });
				fileInput.dispatchEvent(event);
			},200);
		}
		window['show_photo_preview'] = (el) => {
			setTimeout(() => {
				let previewer = document.querySelector(`[data-preview="${el.id}"]`);

				if(previewer == undefined){
					alert_danger('previewer not found');
					return;
				} else {
					// alert_dark('previewer found');

					const img = previewer.querySelector('img');
					const fyl = fileInput.files[0];

					img.style.opacity = 0;

					alert_dark('fetched file input');
					if(fyl && fyl.type.startsWith('image/')){
						const reader = new FileReader();

						reader.onload = (e) => {
							img.src = e.target.result;

							img.onload = () => {
								img.style.opacity = 1;
							}
						}

						reader.readAsDataURL(fyl);
					}
				}
			},300);
		}
		window['save_biz_images'] = (el) => {
			alert_success('saving photos');

			const step1 = (d) => {
				el.admin_password_mine.value = d.admin_password;
				sendform(el);
			}

			awaitConfirmPass(step1)
		}

	// deviation sumary ops
		window['add_deviation_entry'] = () => {
			alert_info('adding deviation entry');
			add_edit_deviations_trig.click();
			add_edit_deviations_title.innerHTML = 'add Deviation Summary Entry';
		}
		window['submit_deviation_entry'] = (el) => {
			alert_dark('submitting');

			let d_data = temp_bizdata.temp.deviationData || [];

			let sample_name = el.sample_name.value;
			let a_method = el.a_method.value;
			let tolerance = el.tolerance.value;
			let myid = el.theid.value;

			let obj = {
				"type" : sample_name,
				"method" : a_method,
				"tolerance" : tolerance,
			};

			const step_addnew = () => {
				d_data.push(obj);
				temp_bizdata.temp.deviationData = d_data;

				final_step();
				alert_dark('added deviation data successfully');
			}
			const step_editone = () => {
				alert_dark('editing item');

				d_data[myid] = obj;
				final_step();
			}

			const final_step = () => {
				el.reset();
				el.theid.value = "";
				add_edit_deviations_trig.click();
				window['rerender_stuff']();
			}

			if(myid == ""){
				step_addnew();
			} else {
				step_editone();
			}
		}
		window['edit_deviation'] = (el) => {
			if(el == undefined){alert_danger('invalid edit_deviation call');return;}

			let mid = el.dataset.myid || -1;
			let arr = temp_bizdata.temp.deviationData;
			if(mid < 0 || mid >= arr.length){alert_danger('invalid index');return;}

			let wot = arr[mid];

			// run ops
			add_edit_deviations_trig.click();
			add_edit_deviations_title.innerHTML = "edit Deviation Entry";
			add_edit_deviations_form.theid.value = mid;
			add_edit_deviations_form.sample_name.value = wot.type;
			add_edit_deviations_form.a_method.value = wot.method;
			add_edit_deviations_form.tolerance.value = wot.tolerance;
		};
		window['remove_deviation'] = (el) => {
			if(el == undefined){alert_danger('invalid remove_deviation call');return;}

			let mid = el.dataset.myid || -1;
			let arr = temp_bizdata.temp.deviationData;
			if(mid < 0 || mid >= arr.length){alert_danger('invalid index');return;}

			const deleteit = () => {
				newarr = [];

				arr.forEach((e,id) => {
					if(id == mid){return;}
					newarr.push(e);
				})
				temp_bizdata.temp.deviationData = newarr;
				alert_success('removed successfully');
				rerender_stuff();
			}

			// run ops
			confirmAction(mekHeading('remove deviation entry','h4'),`this will remove this entry from the deviation summary. <br>are you sure?`,() => {
				deleteit();
			})
		};
		window['save_deviations'] = (skipauth,pw) => {
			alert_dark('trying to save deviation data');

			let raw = temp_bizdata.temp ? temp_bizdata.temp.deviationData : [];
			let tosave = raw;

			if(tosave.length == 0){
				alert_danger('data to be saved cant be empty!',5);
				return;
			}

			const step1 = (dta) => {
				let pushdata = {
					'itemname' : 'deviation_data',
					'itemvalue' : tosave,
				}
				topush = {...pushdata,...dta};

				window[fetch_bypass](linkAtlas['update_bizdata'],topush,'POST').then((w) => {
					responseHandler(w,step2,w);
				})
			};

			const step2 = (d) => {
				let sdta = d.sendme.item_data;
				temp_bizdata.temp.deviationData = sdta;
				final_step();
			}

			const final_step = (dta) => {
				alert_info('biz data retrieved, rerendering...');
				// rerender_stuff();
				initbizstuff();
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

	// business information ops
		window['add_bizinfo'] = () => {
			add_edit_bizinfo_trig.click();

			let b_info = temp_bizdata.temp.bizinfo || {};

			add_edit_bizinfo_form.branch_name.value = b_info.branch_name || '';
			add_edit_bizinfo_form.location.value = b_info.location || '';
			add_edit_bizinfo_form.email.value = b_info.email || '';
			add_edit_bizinfo_form.phone.value = b_info.phone || '';
		}
		window['submit_bizinfo'] = (el) => {
			let b_info = {
				branch_name : el.branch_name.value || '--!--',
				biz_name : el.biz_name.value || '--!--',
				location : el.location.value || '--!--',
				email : el.email.value || '--!--',
				phone : el.phone.value || '--!--',
			}

			temp_bizdata.temp.bizinfo = b_info;
			add_edit_bizinfo_trig.click();
			el.reset();

			rerender_stuff();
		}
		window['save_bizinfo'] = (skipauth,pw) => {
			alert_dark('trying to save business information');

			let raw = temp_bizdata.temp ? temp_bizdata.temp.bizinfo : [];
			let tosave = raw;

			if(tosave == undefined){
				alert_danger('data to be saved cant be empty!',5);
				return;
			}

			const step1 = (dta) => {
				let pushdata = {
					'itemname' : 'business_details',
					'itemvalue' : tosave,
				}
				topush = {...pushdata,...dta};

				window[fetch_bypass](linkAtlas['update_bizdata'],topush,'POST').then((w) => {
					responseHandler(w,step2,w);
				})
			};

			const step2 = (d) => {
				let sdta = d.sendme.item_data;
				temp_bizdata.temp.deviationData = sdta;
				final_step();
			}

			const final_step = (dta) => {
				alert_info('biz data retrieved, rerendering...');
				// rerender_stuff();
				initbizstuff();
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
