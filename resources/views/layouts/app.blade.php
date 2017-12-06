<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Laravel') }}</title>

	<!-- Styles -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link rel="stylesheet"
	  	href="https://cdn.jsdelivr.net/npm/animate.css@3.5.2/animate.min.css"
	  	integrity="sha384-OHBBOqpYHNsIqQy8hL1U+8OXf9hH6QRxi0+EODezv82DfnZoV7qoHAZDwMwEJvSw"
	  	crossorigin="anonymous"
	/>
</head>

<body class="bg-brand-lighter h-screen">
	<div id="app" v-cloak>
		@include('partials.nav')

		<img src="{{ asset('/img/banner.png') }}" class="w-full h-full bg-cover" />

		<hr class="mt-2 mb-2" />


		@yield('content')

		<image-modal></image-modal>
	</div>

	<!-- Scripts -->
	<script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
