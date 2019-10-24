@extends('layouts/main')

@section('css')
	<link rel='stylesheet' href="{{ asset('css/thread.css') }}">
@endsection

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-6 offset-3" id="user-img-wrap">
				
			</div>
		</div>
		<div class="row" id="header">
			<div class="col-9">
				<h1 class='text-left'>{{$user->name}}</h1>
			</div>
			<div class="col-3">
				<h1 class='text-right'>Points: {{$user->reputation}}</h1>
			</div>
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
