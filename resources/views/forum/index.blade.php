@extends('layouts/main')

@section('css')
	<link rel='stylesheet' href="{{ asset('css/forum.css') }}">
@endsection

@section('content')
	<div class="container">
		@foreach ($threads as $t)
				<div class="thread" onclick='window.location="forum/{{$t->id}}"'>
					<div class="row thread-header">
						<div class="col-4 text-left">
							<h4 class='thread-user'><a href='user/{{$t->user->id}}'>{{$t->user->name}}</a></h4>
							<p class='thread-date'><small><em>{{$t->created_at->diffForHumans()}}</em></small></p>
						</div>
						<div class="col-8 text-right">
							<h2 class='thread-title'>{{$t->title}}</h2>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-12">
							<p class='thread-body'>{{$t->body}}</p>
						</div>
					</div>
				</div>

		@endforeach

	</div>
@endsection

@section('javascript')
	<script src='{{ asset("js/forum.js") }}'></script>
@endsection
