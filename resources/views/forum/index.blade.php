@extends('layouts/main')

@section('css')
	<link rel='stylesheet' href="{{ asset('css/forum.css') }}">
@endsection

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-9">
				@forelse ($threads as $t)
					<div class="thread panel" onclick='window.location="{{$t->getPath()}}"'>
						<div class="row thread-header">
							<div class="col-3 text-left">
								<div>
									<img src='{{$t->user->image}}' class='user-thumbnail'>
								</div>
								<div>
									<a href='{{$t->user->getPath()}}'>{{$t->user->name}}</a></div>
								<div>
									<small><span>posted in: </span>{{$t->category->name}}</span></small>
								</div>
							</div>
							<div class='col-8 text-left'>
								<h2 class='thread-title'>
									@if ($t->hasBeenUpdated())
										<b>{{$t->title}}</b>
									@else
										{{$t->title}}
									@endif
								</h2>
								@if ($t->is_locked)
									<div class="font-weight-light">
										<i class="fas fa-lock" style="font-size: 0.9rem;"></i>
										<span style="font-size: 0.9rem;">Locked</span>
									</div>
								@endif
							</div>
							<div class="col-1 text-right">
								@can('favourite',$t)
									<favourite :item="{{$t}}" :type="'thread'" class='favourite-wrapper'></favourite>
								@endcan
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-12">
								<p class='thread-body'>{!! str_limit($t->body,400, '...</div>') !!}</p>
							</div>
						</div>
						<div class="row">
							<div class='col-12 text-center'>
								<small>
								<i class="fas fa-clock thread-meta-icon"></i>{{ $t->created_at->diffForHumans() }}&nbsp;,&nbsp;
								<i class="fas fa-comment thread-meta-icon"></i>{{$t->replies_count}}&nbsp;,&nbsp;
								<i class="fas fa-eye thread-meta-icon"></i>{{ $t->visits }}
								</small>
							</div>
						</div>
					</div>
				@empty
					<br><hr>
					<h3 class='text-center'>Nothin' to see here folks</h3>
					<hr>
				@endforelse
				{{ $threads->links() }}
			</div>

			<div class="col-3">
				@include('particals/sidebar')
			</div>
		</div>


	</div>
@endsection

@section('javascript')
	<script src='{{ asset("js/forum.js") }}'></script>
@endsection
