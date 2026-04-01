<!-- dont forget to activate the form csrf region -->

<x-layouts.mainlayout>
	<style>
		.logo_img_ img {
			/* aspect-ratio: auto; */
			height: 150px;
			width: auto;
		}
	</style>

	<div class="loginHolder fullcon flow centroid">
		<div class="panelbg spacy-md formholder mycon sm themeround slide-up">
			<div class="w3-center">
				<div class="border-bottom pb-2">
					<div class="logo_img_ flow centroid">
						<img src="./_assets/img/logo_full.png">
					</div>
					<!-- <span>Internal Operations management system</span> -->
				</div>
				<!-- <hr> -->
				<div class="spacy-sm">
					<span>enter your details to begin your session</span>
				</div>
			</div>

			<div>
				<form method="post" action="./login">
					{{-- <!-- --}}
					@csrf
					{{-- --> --}}
					<div class="input-group-custom">
						<div class="form-group">
							<label class="form-label" for="email">Email Address</label>
							<input type="email" class="form-control-custom" id="email" name="email" placeholder="your email" required>
						</div>
					</div>
					<div class="input-group-custom">
						<div class="form-group">
							<label class="form-label" for="password">Password</label>
							<input type="password" class="form-control-custom" id="password" name="password" placeholder="enter password" required>
						</div>
					</div>
					<div class="distance-sm">
						<div class="custom-control custom-switch">
							<input type="checkbox" class="custom-control-input" id="remember" name="Remember">
							<label class="custom-control-label" for="remember">Remember me</label>
						</div>
					</div>
					<div>
						<button class="mybtn primary in_fullwidth w3-center">
							log in
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</x-layouts.mainlayout>
