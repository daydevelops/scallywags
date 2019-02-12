@extends('layouts/main')

@section('css')
	<link rel='stylesheet' href="{{ asset('css/thread.css') }}">
@endsection

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-12" id="header">
				<h1 class='text-center'>{{$user->name}}</h1>
			</div>
		</div>
		<div class='row'>
			<div class='col-12'>
				@foreach ($activities as $act)
					@include ('users/activities/'.$act->type)
				@endforeach
			</div>
		</div> <!-- row -->
	</div>

	@include('components.areYouSureModal')

@endsection

@section('javascript')
	<script src='{{ asset("js/forum.js") }}'></script>
	<script src='{{ asset("js/thread.js") }}'></script>
@endsection
