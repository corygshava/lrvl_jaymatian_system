<x-layouts.mainlayout>
	<div>
		<div class="loginHolder fullcon flow centroid">
			<div class="panelbg spacy-md formholder mycon sm themeround slide-up">
				<div class="w3-center">
					<span class="h3">JMATT</span>
					<p>management system</p>
					<hr>
					<p>enter your details to log in</p>
				</div>
				<form method="post" action="./login">
                    @csrf
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
