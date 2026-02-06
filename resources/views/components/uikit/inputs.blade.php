@props([
	"data" => [],
])

<?php
	$in = $data;
	$type = $in['type'] ?? 'text';
	$field = $in['field'] ?? 'myfield';
	$label = $in['label'] ?? 'field';
	$placeholder = $in['placeholder'] ??  "enter ".$label." here...";
	$options = $in['options'] ?? [];
	$checked = $in['checked'] ?? false;
	$required = $in['required'] ?? true;
	$readonly = $in['readonly'] ?? false;
	$value = $in['value'] ?? '';
	$style = $in['style'] ?? '';
	$xtraclass = $in['class'] ?? '';

	$is_required = !empty($required) ? 'required' : '';
	$is_checked = !empty($checked) ? 'checked' : '';
	$is_readonly = !empty($readonly) ? 'readonly' : '';
?>

@if ($type == "whot")
@else @if ($type == "select")
	<div class="form-group">
		<label class="form-label" for="{{$field}}">{{$label}}</label>
		<select id="{{$field}}" name="{{$field}}" class="form-control bg-dark w3-text-white {{$xtraclass}}" style="{!! $style !!}" {{$is_required}} {{$is_readonly}}>
			<option selected disabled>{{$placeholder}}</option>
			@foreach ($options as $opt)
				<?php $is_selected = isset($opt['selected']) ? ($opt['selected'] ? 'selected' : '') : ''; ?>

				@if (isset($opt['val']))
					<option value="{{$opt['val']}}" {{ $is_selected }}>{{$opt['cap']}}</option>
				@else
					<option value="{{$opt}}">{{$opt}}</option>
				@endif
			@endforeach
		</select>
	</div>
@else @if ($type == "hidden")
	<input type="{{$type}}" name="{{$field}}" id="{{$field}}" placeholder="{{$placeholder}}" value="{{$value}}" {{$is_required}} {{$is_readonly}}>
@else @if ($type == "checkbox")
	<?php
		$is_checked = !$is_checked ? $value == true : $is_checked;
	?>
	<div class="custom-control custom-checkbox mb-4  {{$xtraclass}}">
		<input type="checkbox" class="custom-control-input {{$xtraclass}}" style="{!! $style !!}" id="{{$field}}" name="{{$field}}" {{$is_checked}} {{$is_required}}>
		<label class="custom-control-label" for="{{$field}}">{{$label}}</label>
	</div>
@else @if ($type == "textarea")
	<div class="form-group">
		<label class="form-label" for="{{$field}}">{{$label}}</label>
		<textarea type="{{$type}}" class="form-control bg-dark w3-text-white {{$xtraclass}}" style="{!! $style !!}" rows="3" name="{{$field}}" id="{{$field}}" placeholder="{{$placeholder}}" {{$is_required}} {{$is_readonly}}>{{$value}}</textarea>
	</div>
@else @if ($type == "color")
	<div class="form-group">
		<label class="form-label" for="{{$field}}">{{$label}}</label>
		<input type="{{$type}}" class="form-control bg-dark w3-text-white {{$xtraclass}}" style="{!! $style !!}" name="{{$field}}" id="{{$field}}" placeholder="{{$placeholder}}" value="{{$value}}" {{$is_required}} {{$is_readonly}}>
	</div>
@else @if ($type == "all")
	<!-- Text Inputs -->
	<div class="form-group">
		<label class="form-label" for="text">Text</label>
		<input type="text" class="form-control-custom" id="text" placeholder="Enter text">
	</div>

	<div class="form-group">
		<label class="form-label" for="password">Password</label>
		<input type="password" class="form-control-custom" id="password" placeholder="Enter password">
	</div>

	<div class="form-group">
		<label class="form-label" for="email">Email address</label>
		<input type="email" class="form-control-custom" id="email" placeholder="name@example.com">
	</div>

	<div class="form-group">
		<label class="form-label" for="number">Number</label>
		<input type="number" class="form-control-custom" id="number" placeholder="12345">
	</div>

	<div class="form-group">
		<label class="form-label" for="tel">Telephone</label>
		<input type="tel" class="form-control-custom" id="tel" placeholder="+254700000000">
	</div>

	<div class="form-group">
		<label class="form-label" for="url">URL</label>
		<input type="url" class="form-control-custom" id="url" placeholder="https://example.com">
	</div>

	<div class="form-group">
		<label class="form-label" for="search">Search</label>
		<input type="search" class="form-control-custom" id="search" placeholder="Search...">
	</div>
@else
	<div class="form-group">
		<label class="form-label" for="{{$field}}">{{$label}}</label>
		<input type="{{$type}}" class="form-control-custom {{$xtraclass}}" style="{!! $style !!}"
			name="{{$field}}" id="{{$field}}" placeholder="{{$placeholder}}" value="{{$value}}"
			aria-describedby="help_{{$field}}" {{$is_required}} {{$is_readonly}}
		/>
		<small id="help_{{$field}}" class="text-muted"></small>
	</div>
@endif
@endif
@endif
@endif
@endif
@endif
@endif
