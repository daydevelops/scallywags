@extends('layouts/main')

@section('css')
	<link rel='stylesheet' href="{{ asset('css/thread.css') }}">
@endsection

@section('content')
	<div class="container">
		<div class='row'>
			<div class='col-9'>
				<div class="thread">
					<div class="row thread-header">
						<div class="col-8 text-left">
							<h2 class='thread-title'>{{$thread->title}}</h2>
							<p class='thread-category'><small><em>{{$thread->category->name}}</em></small></p>
						</div>
						<div class="col-4 text-right">
							<h4 class='thread-user'><a href='user/{{$thread->user->id}}'>{{$thread->user->name}}</a></h4>
							<p class='thread-date'><small><em>{{$thread->created_at->diffForHumans()}}</em></small></p>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-12">
							<p class='thread-body'>{{$thread->body}}</p>
						</div>
					</div>
				</div>
				@auth
					<div id='new-reply-btn' class="row">
						<div class="col-12">
							<button class="{{count($errors)?'hidden':'d-block'}} btn btn-primary m-auto" onclick='showReplyForm()'>New Reply</button>
						</div>
					</div>
				@endauth

				@guest
					<p class='text-center'>Please <a href='/login'>sign in</a> to comment</p>
				@endguest
				<div id="new-reply-wrap" class='{{count($errors)?"":"hidden"}}'>
					@include('components.error')
					<form method='POST' action="/forum/{{$thread->category->slug}}/{{$thread->id}}/reply">
						@csrf
						<div class="row">
							<div class="col-8 offset-2">
								<div class="form-group">
									<textarea class='form-control' name="body" id="new-reply" rows="5" placeholder="Have something to say?" value="{{old('body')}}" required></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-8 offset-2 text-right">
								<div class='form-group'>
									<button type='submit' class="btn btn-primary d-inline m-auto">Submit</button>
									<button type='button' class="btn btn-danger d-inline m-auto" onClick='hideReplyForm()'>Cancel</button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<br>
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
				{{ $replies->links() }}
			</div>
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
		</div> <!-- row -->
	</div>
@endsection

@section('javascript')
	<script src='{{ asset("js/thread.js") }}'></script>
@endsection
