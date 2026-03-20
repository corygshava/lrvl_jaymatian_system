<div>

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
							<div class="form-group">
								<label class="form-label" for="tech_staff">Technical Staff</label>
								<input type="tel" class="form-control-custom" id="tech_staff" name="tech_staff" placeholder="the technical staff who will appear in reports" required>
							</div>
							<div class="form-group">
								<label class="form-label" for="auth_staff">Authorizer</label>
								<input type="tel" class="form-control-custom" id="auth_staff" name="auth_staff" placeholder="the authorizing staff who will appear in reports" required>
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
