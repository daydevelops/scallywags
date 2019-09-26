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
							<div class="col-10 text-Center">
								<h2 class='thread-title'>
									@if ($t->hasBeenUpdated())
										<b>{{$t->title}}</b>
									@else
										{{$t->title}}
									@endif
								</h2>
							</div>
							<div class="col-2 text-right">
								@can('favourite',$t)
									<favourite :item="{{$t}}" :type="'thread'" class='favourite-wrapper'></favourite>
								@endcan
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-12">
								<p class='thread-body'>{!!$t->body!!}</p>
							</div>
						</div>
						<div class="row">
							<div class='col-12 text-center'>
								<img src='/storage/{{$t->user->image}}' class='user-thumbnail'>
								<a href='{{$t->user->getPath()}}'>{{$t->user->name}}</a>&nbsp;,&nbsp;
								<i class="fas fa-clock"></i>{{ $t->created_at->diffForHumans() }}&nbsp;,&nbsp;
								<i class="fas fa-comment"></i>{{$t->replies_count}}&nbsp;,&nbsp;
								<i class="fas fa-eye"></i>{{ $t->visits }}
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
