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
								<div id="thread-meta-wrap" class="col-12 text-center">
									<img src='/storage/{{$thread->user->image}}' class='user-thumbnail'>
									<a href='{{$thread->user->getPath()}}'>{{$thread->user->name}}</a>&nbsp;,&nbsp;
									<i class="fas fa-clock"></i>{{ $thread->created_at->diffForHumans() }}&nbsp;,&nbsp;
									<i class="fas fa-comment"></i>{{$thread->replies_count}}&nbsp;,&nbsp;
									<i class="fas fa-eye"></i>{{ $thread->visits }}
								</div>
								<div id="thread-title-wrap" class="col-12 text-center">
									<h2 class='thread-title'>{{$thread->title}}</h2>
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
									@can('favourite',$thread)
										<favourite :item="{{$thread}}" :type="'thread'" class='favourite-wrapper d-inline'></favourite>
									@endcan
									@can('subscribe',$thread)
										<subscribe-button :init_subscribed={{$thread->is_subscribed ? "1" : "0"}} class='d-inline'></subscribe-button>
									@endcan
									@if(auth()->check() && auth()->user()->is_admin)
										<form class='d-inline' method='post' action='{{$thread->getPath()}}{{$thread->is_locked?"/unlock":"/lock"}}'>
											@csrf
											<button class='btn btn-warning' id="lock-btn">{{$thread->is_locked?"Unlock":"Lock"}}</button>
										</form>
									@endif
								</div>
							</div>
						</div>

						<replies :locked="{{$thread->is_locked}}" :page="{{$page}}" :best_id='{{$thread->best_reply_id?$thread->best_reply_id:0}}' @add="replies_count++" @removed="replies_count--"></replies>

					</div>
				</div>
			</thread>
			<div class="col-3">
				@include('particals/sidebar')
			</div>
		</div>
	</div>

	@include('components.areYouSureModal')

@endsection

@section('javascript')
	<script src='{{ asset("js/forum.js") }}'></script>
	<script src='{{ asset("js/thread.js") }}'></script>
	<script>
		window.is_thread_owner = {{$thread->user->id == auth()->id() ? 1 : 0}};
	</script>
@endsection
