@extends('layouts/main')

@section('css')
	<link rel='stylesheet' href="{{ asset('css/forum.css') }}">
@endsection

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-9">
				@foreach ($threads as $t)
					<div class="thread panel" onclick='window.location="{{$t->getPath()}}"'>
						<div class="row thread-header">
							<div class="col-8 text-left">
								<p class='thread-user'>
									<a href='user/{{$t->user->id}}'>{{$t->user->name}}</a>
									<span class='thread-date'><small><em>{{$t->created_at->diffForHumans()}}</em></small></span>
								</p>
								<h2 class='thread-title'>{{$t->title}}</h2>
							</div>
							<div class="col-4 text-right">
								<p class='thread-reply-count'><small><em>{{$t->replies_count}} {{str_plural('comment',$t->replies_count)}}</em></small></p>
								@auth
									@if($t->user->id !== auth()->id())
										<p id ='thread-{{$t->id}}' class='favourite-wrapper {{$t->isFavourited()?'favourited':' '}}'><i class="fas fa-heart" onclick='toggleFavourite("thread",{{$t->id}})'></i></p>
									@endif
								@endauth
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
				{{ $threads->links() }}
			</div>
			<div class="col-3">
				<div id="sidebar" class='panel'>
					<h3 class='text-center'>Welcome to the RballNL Forum!</h3>
					<p class='text-center'><small>Here you can ask general questions to other users, share tips, provide updates, etc.</small></p>
					<hr>
					<a class='btn btn-primary btn-lrg d-block' href='/forum/new'>Submit New Post</a>
					<hr>
					<div class="nav-item dropdown" id='categories-dropdown'>
						<a class="nav-link dropdown-toggle text-center" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Categories
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							@foreach ($categories as $c)
								<a class="dropdown-item text-center" href="/forum/{{$c->slug}}">{{$c->name}}</a>
							@endforeach
						</div>
					</div>
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
