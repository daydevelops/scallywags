@extends('layouts/main')

@section('css')
	<link rel='stylesheet' href="{{ asset('css/forum.css') }}">
@endsection

@section('content')
	<header>
		<h1>{{$thread->title}}</h1>
		<p>{{$thread->body}}</p>
		<hr>
		{{-- @foreach($replies as $r)
			<div class="reply-wrapper">
				<p class='reply'>
					{{$r->body}}
				</p>
			</div>
		@endforeach --}}
	</header>
@endsection

@section('javascript')
	<script src='{{ asset("js/forum.js") }}'></script>
@endsection
