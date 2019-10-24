<div v-if="!editing" class="thread">
	<div class="row thread-header">
		<div class="col-3 text-left">
			<div>
				<img src='{{$thread->user->image}}' class='user-thumbnail'>
			</div>
			<div>
				<a href='{{$thread->user->getPath()}}'>{{$thread->user->name}}</a></div>
			<div>
				<small><span>posted in: </span>{{$thread->category->name}}</span></small>
			</div>
		</div>
		<div class='col-7 text-center'>
			<h2 class='thread-title'>
				{{$thread->title}}
			</h2>
		</div>
		<div class="col-2 text-right">
			@can('favourite',$thread)
				<favourite :item="{{$thread}}" :type="'thread'" class='favourite-wrapper'></favourite>
			@endcan
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-12">
			<p class='thread-body'>{!! $thread->body !!}</p>
		</div>
	</div>
	<div class="row">
		<div class="col-6 level">
			<small><small>
			@can('update',$thread)
			<span class='thread-controls' onclick='showAYSM("delete","thread",{{$thread->id}},"{{$thread->getPath()}}")'>Delete</span> |
			<span class='thread-controls' @click="editing=true">Update</span> |
			@endcan
			@can('subscribe',$thread)
			<subscribe-button :init_subscribed={{$thread->is_subscribed ? "1" : "0"}} class='d-inline'></subscribe-button>
			@endcan
			@if(auth()->check() && auth()->user()->is_admin)
			<form class='d-inline' method='post' action='{{$thread->getPath()}}{{$thread->is_locked?"/unlock":"/lock"}}'>
				@csrf
				| <button class='thread-controls' type='submit' id="lock-btn">{{$thread->is_locked?"Unlock":"Lock"}}</button>
			</form>
			@endif
		</small></small>
		</div>
		<div class="col-6 text-right">
			<small><i class="fas fa-clock thread-meta-icon"></i>{{ $thread->created_at->diffForHumans() }}</small>
			<small><i class="fas fa-comment thread-meta-icon"></i>{{$thread->replies_count}}</small>
			<small><i class="fas fa-eye thread-meta-icon"></i>{{ $thread->visits }}</small>
		</div>
	</div>
</div>