<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title')</title>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	@yield('css')

	<script>
	window.App = {!! json_encode([
		'signedIn' => Auth::check(),
		'user' => Auth::user()
		]) !!};
		</script>
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	</head>
	<body>
		<div id="app">

			<nav class="navbar navbar-light navbar-expand-md justify-content-center" id='main-nav'>
				<h3><a href="/forum" class="navbar-brand d-flex mr-auto">ScallyWags</a></h3>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsingNavbar3">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="navbar-collapse collapse" id="collapsingNavbar3">
					<ul class="navbar-nav">
						<li class="nav-item">
							<a class="nav-link" href="/forum">Home</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/forum?popular=1">Popular</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/forum?unanswered=1">Unanswered</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/contact">Contact</a>
						</li>
					</ul>
					<ul class="nav navbar-nav ml-auto w-100 justify-content-end">
						@guest
							<li class="nav-item">
								<a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
							</li>
						@else
							<notification :notifications="[]"></notification>
							<li class="nav-item dropdown">
								<a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
									{{ Auth::user()->name }} <span class="caret"></span>
								</a>

								<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
									<a class="dropdown-item" href="/forum?u={{auth()->id()}}">My posts</a>
									<a class="dropdown-item" href="/favourites">My favourites</a>
									<a class="dropdown-item" href="{{ route('logout') }}"
									onclick="event.preventDefault();
									document.getElementById('logout-form').submit();">
									{{ __('Logout') }}
								</a>

								<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
									@csrf
								</form>
							</div>
						</li>
					@endguest
				</ul>
			</div>
		</nav>


		@yield('content')
		<flash message="{{session('message')}}" class="{{session('alert-type')}}"></flash>
	</div>
	<script src="{{ asset('js/app.js') }}"></script>
	@yield('javascript')
</body>
</html>
