@extends('layouts/main')

@section('css')
	<link rel='stylesheet' href="{{ asset('css/vendor/jquery.atwho.min.css') }}">
	<link rel='stylesheet' href="{{ asset('css/thread.css') }}">
	<style>
	[v-cloak] {display:none}
	</style>
@endsection

@section('content')
	<div class="container">
		<div class='row'>
			<div class='col-9'>
				<thread :initial_replies_count="{{$thread->replies_count}}" inline-template>
					<div>
						<div class="thread">
							<div class="row thread-header">
								<div class="col-8 text-left">
									<p class='thread-user'>
										<img src='/storage/{{$thread->user->image}}' class='user-thumbnail'>
										<small><a href='/profile/{{$thread->user->id}}'>{{$thread->user->name}}</a> |
											<span class='thread-date'>{{$thread->created_at->diffForHumans()}} | {{$thread->category->name}}</span></small>
										</p>
										<h2 class='thread-title'>{{$thread->title}}</h2>
									</div>
									<div class="col-4 text-right">
										<p class='thread-reply-count'><small><em v-text="replies_count">{{str_plural('comment',$thread->replies_count)}}</em></small></p>
										@can('favourite',$thread)
											<favourite :item="{{$thread}}" :type="'thread'" class='favourite-wrapper d-inline'></favourite>
										@endcan
										@can('subscribe',$thread)
											<subscribe-button :init_subscribed={{$thread->is_subscribed ? "1" : "0"}} class='d-inline'></subscribe-button>
										@endcan
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-12">
										<p class='thread-body'>{{$thread->body}}</p>
									</div>
								</div>
								<div class="row">
									<div class="col-12 level">
										@can('update',$thread)
											<button class='btn btn-danger' onclick='showAYSM("delete","thread",{{$thread->id}},"{{$thread->getPath()}}")'>Delete</button>
										@endcan
									</div>
								</div>
							</div>

							<replies :page="{{$page}}" @add="replies_count++" @removed="replies_count--"></replies>

						</div>
					</div>
				</thread>
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

		@include('components.areYouSureModal')

	@endsection

	@section('javascript')
		<script src='{{ asset("js/forum.js") }}'></script>
		<script src='{{ asset("js/thread.js") }}'></script>
	@endsection
