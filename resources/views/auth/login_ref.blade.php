{{--
<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
--}}

{{-- @include('pieces/checkrequest') --}}

<div>
    <div class="loginHolder fullcon flow centroid">
		<div class="panelbg spacy-md formholder mycon sm themeround slide-up">
			<div class="w3-center">
				<span class="h3">JMATT</span>
				<p>management system</p>
				<hr>
				<p>enter your details to log in</p>
			</div>
			<form method="post">
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
				<div>
					<button class="mybtn primary in_fullwidth w3-center" onclick="saveStudent()">
						log in
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
