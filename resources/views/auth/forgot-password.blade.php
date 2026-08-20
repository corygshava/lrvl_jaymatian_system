<!-- dont forget to activate the form csrf region -->

<x-layouts.mainlayout>
	<style>
		.logo_img_ img {
			/* aspect-ratio: auto; */
			height: 150px;
			width: auto;
		}
		text-green-600{
			color: var(--success);
		}
	</style>


	<div class="loginHolder fullcon flow centroid">
		<div class="panelbg spacy-md formholder mycon sm themeround slide-up">
			<div class="logo_img_ flow centroid">
				<img src="./_assets/img/logo_full.png">
			</div>

			<div class="mb-4 text-sm">
				{{ __('enter your email to reset your password') }}
			</div>

			<!-- Session Status -->
			<x-auth-session-status class="mb-4" :status="session('status')" />

			<form method="POST" action="{{ route('password.email') }}">
				@csrf

				<!-- Email Address -->
				<div class="input-group-custom">
					<div class="form-group">
						<label class="form-label" for="email">Email Address</label>
						<input type="email" class="form-control-custom" id="email" name="email" placeholder="your email" value="{{ old('Email') }}" required>
						<x-input-error :messages="$errors->get('email')" class="mt-2" />
					</div>
				</div>

				<div class="flex items-center justify-end mt-4">
					<button class="mybtn primary in_fullwidth w3-center">
						email password reset link
					</button>
				</div>
			</form>
		</div>
	</div>
</x-guest-layout>
