<x-layouts.mainlayout>
    @php
        $type="email";
        $name="password";
        $name="password_confirmation";

        $inputs = [
            [
                "field" => "name",
                "label" => "username",
                "required" => true,
            ],
            [
                "field" => "email",
                "label" => "Email address",
                "type" => "email",
                "required" => true,
            ],
            [
                "field" => "password",
                "label" => "Enter password",
                "type" => "password",
                "placeholder" => "Enter your password",
                "required" => true,
            ],
            [
                "field" => "password_confirmation",
                "label" => "Confirm password",
                "type" => "password",
                "placeholder" => "Repeat your password",
                "required" => true,
            ],
        ];
    @endphp

	<div>
		<div class="loginHolder fullcon flow centroid">
			<div class="panelbg spacy-md formholder mycon sm themeround slide-up">
				<div class="w3-center">
					<span class="h3">JMATT</span>
					<p>management system</p>
					<hr>
					<p>enter your details to register your account</p>
				</div>
				<form method="post" action="./register">
                    @csrf

                    @foreach ($inputs as $i)
                        <div class="input-group-custom">
                            <x-uikit.inputs :data="$i"/>
                        </div>
                    @endforeach

					<div>
						<button class="mybtn primary in_fullwidth w3-center">
							Register User
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

    {{--
        name="name"
        type="email"
        name="password"
        name="password_confirmation"
    --}}
</x-layouts.mainlayout>
