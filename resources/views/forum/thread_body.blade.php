<div v-if="!editing" class="thread">
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
				<button class='btn btn-info' @click="editing=true">Update</button>
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
