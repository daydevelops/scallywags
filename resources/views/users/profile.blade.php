@extends('layouts/main')

@section('css')
<link rel='stylesheet' href="{{ asset('css/thread.css') }}">
<link rel='stylesheet' href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="container">
	<div id="header" class="d-flex flex-column align-items-center">
		<img class="d-block ml-auto mr-auto mt-3 rounded-circle" src="{{$user->image}}" class='user-thumbnail'>
		<h3 class='text-center'>{{$user->name}}</h3>
		<h4 class='text-center'>Rep: {{$user->reputation}}</h4>
		@if($chat_exists)
		<p class="btn btn-small btn-primary"><a href="/chats">Message</a></p>
		@else
		<p class='m-auto btn btn-small btn-primary' data-toggle="modal" data-target="#new-msg-modal">Message</p>
		<div class="modal" id="new-msg-modal" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Send {{$user->name}} a message!</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form id="new-chat-form" action="/chats" method="POST">
						@csrf
						<div class="modal-body">
							<textarea id="new-chat-msg" name="message"></textarea>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary">Send</button>
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		@endif
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
<script>
	var profile_user_id = {{$user->id}};
</script>
<script src='{{ asset("js/profile.js") }}'></script>
@endsection