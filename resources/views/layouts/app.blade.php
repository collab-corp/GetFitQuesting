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
		<nav class="bg-blue-lightest h-12 shadow mb-8">
			<div class="container mx-auto h-full">
				<div class="flex items-center justify-center h-12">
					<div class="mr-6">
						<a href="{{ url('/') }}" class="no-underline text-black">
							{{ config('app.name', 'Laravel') }}
						</a>
					</div>

					<div class="mr-8 text-sm">
						<a href="{{ url('/about') }}" class="no-underline hover:underline text-brand-darkest pr-2">
							{{ trans('menu.links.about') }}
						</a>
						<a href="{{ url('/armory') }}" class="no-underline hover:underline text-brand-darkest pr-2">
							{{ trans('menu.links.armory') }}
						</a>
                        <a href="{{ url('/schedule') }}" class="no-underline hover:underline text-brand-darkest pr-2">
                            {{ trans('menu.links.schedule') }}
                        </a>
                        <a href="{{ url('/community') }}" class="no-underline hover:underline text-brand-darkest pr-2">
                            {{ trans('menu.links.community') }}
                        </a>
					</div>

					<div class="flex-1 text-right text-sm">
						@guest
						<a class="no-underline hover:underline pr-3 text-brand-darkest" href="{{ url('/login') }}">Login</a>
						<a class="no-underline hover:underline text-brand-darkest" href="{{ url('/register') }}">Register</a>
						@else
						<span class=" pr-4">{{ Auth::user()->name }}</span>

						<a href="{{ route('logout') }}" class="no-underline hover:underline text-brand-darkest" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">Logout</a>
						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
							{{ csrf_field() }}
						</form>
						@endguest
					</div>
				</div>
			</div>
		</nav>

		<img src="{{ asset('/img/banner.png') }}" class="w-full h-full bg-cover" />

		<hr class="mt-2 mb-2" />


		@yield('content')

		<image-modal></image-modal>
	</div>

	<!-- Scripts -->
	<script src="{{ asset('js/app.js') }}"></script>
</body>

</html>