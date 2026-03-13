<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error {{ $code ?? 'Unknown' }}</title>

	<link rel="stylesheet" href="{{ url('/_assets/css/coryG_base.css') }}"/>
	<link rel="stylesheet" href="{{ url('/_assets/css/inter_slop.css') }}"/>

    <style>
		body {
            font-family: system-ui, -apple-system, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: var(--clr-bg);
            color: var(--clr-text);
        }
        .error-container {
            text-align: center;
            padding: 2rem;
        }
        span.hed {
			font-size: 6rem;
			font-weight: 700;
			margin: 0;
		}
        /* p { font-size: 1.5rem; margin: 1rem 0; } */
    </style>
</head>
<body>
    <div class="error-container">
		<div>
			<span class="hed">Error {{ $code ?? '500' }}</span>
			<p>{{ $message ?? 'Something went wrong.' }}</p>
		</div>
		<div class="spacy-sm">
			<a class="mybtn primary" href="{{ url('/') }}">Homepage</a>
		</div>
    </div>
</body>
</html>
