<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">


	<title>Error 500</title>


	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,900" rel="stylesheet">


	<link type="text/css" rel="stylesheet" href="{{ URL::to('css/errors/404.css') }}" />



</head>

<body>

	<div id="error">
		<div class="error">
			<div class="error404">
				<h3>Oops! Internal Server Error</h3>
				<h1><span>5</span><span>0</span><span>0</span></h1>
			</div>
			<h2>Please contact the Vyuh Team if the error persists</h2>
			<a href="{{ url('/') }}">Back to home</a>
		</div>
	</div>

</body>

</html>
