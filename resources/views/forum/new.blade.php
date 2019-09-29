@extends('layouts/main')

@section('css')
	<link rel='stylesheet' href="{{ asset('css/forum.css') }}">
	<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/trix/1.1.0/trix.css'>
@endsection

@section('content')
	<div class="container">
		<h2 class='text-center'>Add a new discussion...</h2>
		@include('components.error')
		<form id='new-thread-form' action='/forum' method='POST'>
			@csrf
			<div class="form-group">
				<div class="row">
					<div class="col-8">
						<input type="text" class="form-control" name='title' id="title" placeholder="Title..." value="{{old('title')}}" required>
					</div>
					<div class="col-4">
						<select class='form-control d-inline' name="category_id">
							@foreach ($categories as $c)
								<option value="{{$c->id}}" {{old('category_id')==$c->id?'selected':''}}>{{$c->name}}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
			<div class="form-group">
				<wysiwyg :content="'Something to say?'" :name="'body'"></wysiwyg>
				{{-- <textarea  class="form-control" name='body' id="body" placeholder="..." rows="10" value="{{old('body')}}" required></textarea> --}}
			</div>
			<div class='row form-group'>
				<div class="g-recaptcha m-auto" data-sitekey="6LdZ47oUAAAAADz9-BI-0B8ecrjz3S_kN4fvbmcm"></div>
			</div>
			<div class='form-group text-center'>
				<button type='submit' class="btn btn-primary d-inline m-auto">Submit</button>
				<button class="btn btn-danger d-inline m-auto" onClick='window.location="/forum"'>Cancel</button>
			</div>
		</form>
	</div>
@endsection

@section('javascript')
	<script src='{{ asset("js/forum.js") }}'></script>
@endsection
