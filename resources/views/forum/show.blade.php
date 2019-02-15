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
							<p class='thread-user'>
								<small><a href='/profile/{{$thread->user->id}}'>{{$thread->user->name}}</a> |
									<span class='thread-date'>{{$thread->created_at->diffForHumans()}} | {{$thread->category->name}}</span></small>
								</p>
								<h2 class='thread-title'>{{$thread->title}}</h2>
							</div>
							<div class="col-4 text-right">
								<p class='thread-reply-count'><small><em>{{$thread->replies_count}} {{str_plural('comment',$thread->replies_count)}}</em></small></p>
								@can('favourite',$thread)
									<p id='thread-{{$thread->id}}' class='favourite-wrapper  {{$thread->isFavourited()?'favourited':' '}}'><i class="fas fa-heart" onclick='toggleFavourite("thread",{{$thread->id}})'></i></p>
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
						<div class="reply" id='reply-{{$r->id}}'>
							<div class="row reply-header">
								<div class="col-8">
									<b><small><a href='/profile/{{$r->thread->user->id}}'>{{$r->user->name}}</a> | {{$r->created_at->diffForHumans()}}</small></b>
								</div>
								<div class="col-4 text-right">
									@auth
										@if(!$r->deleted)
											@can('favourite',$r)
												<p id='reply-{{$r->id}}' class='favourite-wrapper {{$r->isFavourited()?'favourited':' '}}'><i class="fas fa-heart" onclick='toggleFavourite("reply",{{$r->id}})'></i></p>
											@endcan
										@endif
									@endauth
								</div>
							</div>
							<div class='row reply-body'>
								<div class="col">
									{{$r->body}}
								</div>
							</div>
							<div class="row">
								<div class="col-12 level">
									@can('update',$r)
										<button class='btn btn-danger' onclick='showAYSM("delete","reply",{{$r->id}},"/forum/reply/{{$r->id}}")'>Delete</button>
										<button class="btn btn-secondary">Edit</button>
									@endcan
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

		@include('components.areYouSureModal')

	@endsection

	@section('javascript')
		<script src='{{ asset("js/forum.js") }}'></script>
		<script src='{{ asset("js/thread.js") }}'></script>
	@endsection
