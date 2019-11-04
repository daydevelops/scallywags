@extends('layouts/main')

@section('css')
	<link rel='stylesheet' href="{{ asset('css/thread.css') }}">
@endsection

@section('content')
	<div class="container">
		<div id="header">
			<img class="d-block ml-auto mr-auto mt-3 rounded-circle" src="{{$user->image}}" class='user-thumbnail'>
			<h3 class='text-center'>{{$user->name}}</h3>
			<h4 class='text-center'>Rep: {{$user->reputation}}</h4>
		</div>
		<div class='row'>
			<div class='col-12'>
				@forelse ($activities as $act)
					@include ('users/activities/'.$act->type)
				@empty
					<p class='text-center'>This user does not yet have any activity</p>
				@endforelse
			</div>
		</div> <!-- row -->
	</div>

	@include('components.areYouSureModal')

@endsection

@section('javascript')
	<script src='{{ asset("js/forum.js") }}'></script>
	<script src='{{ asset("js/thread.js") }}'></script>
@endsection
