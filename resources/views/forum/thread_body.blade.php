<div v-if="!editing" class="thread">
	<div class="row thread-header">
        <div class="col-auto text-left">
            <div>
                <a href='{{$thread->user->getPath()}}'>
                    <img src='{{$thread->user->image}}' class='user-thumbnail'>
                </a>
            </div>
        </div>
        <div class='col text-left'>
            <h1 class='thread-title' style="font-size: 1.5rem;">
                @if ($thread->hasBeenUpdated())
                    <b>{{$thread->title}}</b>
                @else
                    {{$thread->title}}
                @endif
            </h1>
            <div class="help-block thread-details">
                Posted by: {{$thread->user->name}}
                <div class="badge badge-info">{{ $thread->category->name }}</div>
            </div>
        </div>
		<div class="col-2 text-right">
			@can('favourite',$thread)
				<favourite :item="{{$thread}}" :type="'thread'" class='favourite-wrapper'></favourite>
			@endcan
		</div>
	</div>

	<div class="row py-4">
		<div class="col-12">
			<p class='thread-body'>{!! $thread->body !!}</p>
		</div>
	</div>
	<div class="row">
		<div class="col-6 text-left">
			<small><i class="fas fa-clock thread-meta-icon"></i>{{ $thread->created_at->diffForHumans() }}</small>
			<small><i class="fas fa-comment thread-meta-icon"></i>{{$thread->replies_count}}</small>
			<small><i class="fas fa-eye thread-meta-icon"></i>{{ $thread->visits }}</small>
		</div>

		<div class="col-6 level text-right">
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
			<form class='d-inline' method='post' action='{{$thread->getPath()}}{{$thread->is_pinned?"/unpin":"/pin"}}'>
				@csrf
				| <button class='thread-controls' type='submit' id="pin-btn">{{$thread->is_pinned?"Unpin":"Pin"}}</button>
			</form>
			@endif
		</small></small>
		</div>
	</div>
</div>
