@extends('layouts/main')

@section('css')
	<link rel='stylesheet' href="{{ asset('css/dashboard.css') }}">
@endsection

@section('content')

	<header>
		<h1 class='text-center'>{{$user->name}}
			@if ($user->skill !== "NA")
				<span class='user-skill'>[{{$user->skill}}]</span>
			@endif
		</h1>
	</header>
	<section id='personal-info-wrapper'>
		<div class='container-fluid'>
			<div class='row'>
				<div class='col-sm-6' id='profile-image-wrapper'>
					<avatar-form :user="{{$user}}"></avatar-form>
					{{-- <form method='POST' action="/profile/avatar" enctype="multipart/form-data">
						@csrf
						<input type="file" name="avatar">
						<button type='submit'>upload</button>
					</form>
					<img id='user-image' src='/storage/{{$user->image}}'> --}}
				</div>
				<div class='col-sm-6' id='personal-info'>
					<p id='user-name'><b>Name:</b> {{$user->name}}</p>
					@if ($user->skill !== "NA")
						<p id='user-skill'><b>Skill Level:</b> {{$user->skill}}</p>
					@endif
					<p id='user-email'><b>Email Addresss:</b> {{$user->email}}</p>

				</div>
			</div>
			<div class='row'>
				<div class='col-12'>
					<!-- Button trigger modal -->
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#edit-profile-modal">
						Edit Profile
					</button>
				</div>
			</div>
		</div>
	</section>
	<hr>
	<section id='membership-section'>
		<div class='container-fluid'>
			<div class='row'>
				<div class='col-12 text-center'>
					<p>Membership Status: <span id='membership-status'>Up to date :)</span></p>
					<p>Next Renewal date: <span id='renewal-date'>NEVARRR</span></p>
				</div>
			</div>
		</div>
	</section>
	<hr>
	<section id='forum-section'>
		<div class='container-fluid'>
			<div class='row'>
				<div class='col-12 text-center'>
					<p><a href='/forum?u={{auth()->id()}}'>View Your Forum Activity</a></p>
				</div>
			</div>
			<div class='row'>
				<div class='offset-sm-2 col-sm-4 text-center'>
					<p>Posts: {{$user->threads->count()}}</p>
				</div>
				<div class='col-4 text-center'>
					<p>Replies: {{$user->replies->count()}}</p>
				</div>
			</div>
		</div>
	</section>
	<hr>
	


	<!-- Modal -->
	<div class="modal fade" id="edit-profile-modal" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-body">
					<form>
						<div class="form-group">
							<label for="form-name">User Name</label>
							<input type='text' class='form-control' id='form-name' name='name' value='{{$user->name}}'>
						</div>
						<div>
							<label for="form-email">Email Address</label>
							<input type="email" class="form-control" id="form-email" aria-describedby="emailHelp" value="{{$user->email}}">
						</div>
						<div class="form-group">
							<label for="old-pass">Password</label>
							<input type="password" class="form-control" id="old-pass" placeholder="Old Password">
						</div>
						<div class="form-group">
							<input type="password" class="form-control" id="new-pass" placeholder="New Password">
						</div>
						<div class="form-group">
							<input type="password" class="form-control" id="confirm-pass" placeholder="Confirm Password">
						</div>
						<div class="form-group">
							<label for="form-skill">Skill Level</label>
							<select class="form-control" id="exampleFormControlSelect1">
								<option>1</option>
								<option>2</option>
								<option>3</option>
								<option>4</option>
								<option>I dont know</option>
							</select>
						</div>
						<button type="submit" class="btn btn-primary" data-dismiss="modal">Submit</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					</form>
				</div>
			</div>
		</div>
	</div>


	
@endsection

@section('javascript')
	<script src='{{ asset("js/dashboard.js") }}'></script>
@endsection
