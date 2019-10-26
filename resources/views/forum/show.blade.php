@extends('layouts/main')

@section('css')
	<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/trix/1.1.0/trix.css'>
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
				<thread :thread="{{$thread}}" :initial_replies_count="{{$thread->replies_count}}" inline-template>
					<div>
						@include('forum/thread_body')
						@include('forum/thread_edit_form')
						<replies :locked="{{$thread->is_locked}}" :page="{{$page}}" :best_id='{{$thread->best_reply_id?$thread->best_reply_id:0}}' @add="replies_count++" @removed="replies_count--"></replies>
					</div>
				</thread>
			</div>
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
