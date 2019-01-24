@extends('layouts/main')

@section('css')
	<link rel='stylesheet' href="{{ asset('css/thread.css') }}">
@endsection

@section('content')
	<div class="container">
		<div class="thread">
			<div class="row thread-header">
				<div class="col-4 text-left">
					<h4 class='thread-user'><a href='user/{{$thread->user->id}}'>{{$thread->user->name}}</a></h4>
					<p class='thread-date'><small><em>{{$thread->created_at->diffForHumans()}}</em></small></p>
				</div>
				<div class="col-8 text-right">
					<h2 class='thread-title'>{{$thread->title}}</h2>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-12">
					<p class='thread-body'>{{$thread->body}}</p>
				</div>
			</div>
		</div>

		@foreach($replies as $r)
			<div class="reply">
				<div class="row reply-header">
					<div class="col">
						<b><small>{{$r->user->name}} | {{$r->created_at->diffForHumans()}}</small></b>
					</div>
				</div>
				<div class='row reply-body'>
					<div class="col">
						{{$r->body}}
					</div>
				</div>
			</div>
		@endforeach

	</div>
@endsection

@section('javascript')
	<script src='{{ asset("js/thread.js") }}'></script>
@endsection
