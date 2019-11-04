@extends('layouts/main')

@section('css')
	<link rel='stylesheet' href="{{ asset('css/thread.css') }}">
@endsection

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-6 offset-3" id="user-img-wrap">
				
			</div>
		</div>
		<div class="row">
			<div class="col-12" id="header">
				<h3 class='text-center mt-3'>Favourited Items</h3>
			</div>
		</div>
		<div class='row'>
			<div class='col-12'>
				@forelse ($favourites as $fav)
					@if ($fav->favourited instanceof App\Thread)
                        <div class="thread panel" onclick='window.location="{{$fav->favourited->getPath()}}"'>
						    <div class="row thread-header">
							    <div class="col-10 text-Center">
								    <h2 class='thread-title'>{{$fav->favourited->title}}</h2>
							    </div>
                            </div>
                        </div>
                    @else
                        <div class="thread panel" onclick='window.location="{{$fav->favourited->thread->getPath()}}"'>
						    <div class="row thread-header">
							    <div class="col-10 text-Center">
                                    <p><small>A reply to: {{$fav->favourited->thread->title}}.</small></p>
								    <h2 class='thread-title'>{{$fav->favourited->body}}</h2>
							    </div>
                            </div>
                        </div>
                    @endif
				@empty
					<p class='text-center'>You do not have any favourites yet</p>
				@endforelse
			</div>
		</div> <!-- row -->
	</div>


@endsection

@section('javascript')
	<script src='{{ asset("js/forum.js") }}'></script>
	<script src='{{ asset("js/thread.js") }}'></script>
@endsection
