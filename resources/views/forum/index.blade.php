@extends('layouts/main')

@section('css')
	<link rel='stylesheet' href="{{ asset('css/forum.css') }}">
@endsection

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-9">@foreach ($threads as $t)
				<div class="thread panel" onclick='window.location="{{$t->getPath()}}"'>
					<div class="row thread-header">
						<div class="col-8 text-left">
							<h2 class='thread-title'>{{$t->title}}</h2>
						</div>
						<div class="col-4 text-right">
							<h4 class='thread-user'><a href='user/{{$t->user->id}}'>{{$t->user->name}}</a></h4>
							<p class='thread-date'><small><em>{{$t->created_at->diffForHumans()}}</em></small></p>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-12">
							<p class='thread-body'>{{$t->body}}</p>
						</div>
					</div>
				</div>

			@endforeach</div>
			<div class="col-3">
				<div id="sidebar" class='panel'>
					<h3 class='text-center'>Welcome to the RballNL Forum!</h3>
					<p class='text-center'><small>Here you can ask general questions to other users, share tips, provide updates, etc.</small></p>
					<hr>
					<a class='btn btn-primary btn-lrg d-block' href='/forum/new'>Submit New Post</a>
					<hr>
					<h4 class='text-center'><b>Rules</b></h4>
					<small>
						<ol>
							<li>Profanity prohibited</li>
							<li>Do not share scoring details without your competetors consent</li>
							<li>Do not post spam or marketing links unless explicitly asked</li>
							<li>Be friendly</li>
						</ol>
					</small>
				</div>
			</div>
		</div>


	</div>
@endsection

@section('javascript')
	<script src='{{ asset("js/forum.js") }}'></script>
@endsection
