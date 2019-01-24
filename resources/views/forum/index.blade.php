@extends('layouts/main')

@section('css')
	<link rel='stylesheet' href="{{ asset('css/forum.css') }}">
@endsection

@section('content')
		@foreach ($threads as $t)
			<h2><a href='forum/{{$t->id}}'>{{$t->title}}</a></h2>
			<p>{{$t->body}}</p>
		@endforeach
@endsection

@section('javascript')
	<script src='{{ asset("js/forum.js") }}'></script>
@endsection
