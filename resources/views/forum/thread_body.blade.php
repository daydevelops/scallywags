<div v-if="!editing" class="thread">
	<div class="thread-header">
        <div class="row">
            <div class='col-10'>
				<div class='row'>
                	<div class='col-3'>
						<a href='{{$thread->user->getPath()}}'>
                	    	<img src='{{$thread->user->image}}' class='user-thumbnail'>
						</a>
					</div>
                	<div class='col-9'>
						<span><small>
               				{{$thread->user->name}} {{ $thread->created_at->diffForHumans() }} in
							<span class="badge badge-info">{{ $thread->category->name }}</span>
						</small></span>
					</div>
				</div>
			</div>
			<div class="col-2 text-right">
				@can('favourite',$thread)
					<favourite :item="{{$thread}}" :type="'thread'" class='favourite-wrapper'></favourite>
				@endcan
			</div>
        </div>
        <div class='pt-2'>
            <h1 class='thread-title' style="font-size: 1.5rem;">
                @if ($thread->hasBeenUpdated())
                    <b>{{$thread->title}}</b>
                @else
                    {{$thread->title}}
                @endif 
            </h1>
        </div>
		
	</div>

	<div class="row py-4">
		<div class="col-12">
			<p class='thread-body'>{!! $thread->body !!}</p>
		</div>
	</div>
	<div class="row">
		<div class="col-3 text-left">
			<small><i class="fas fa-comment thread-meta-icon"></i>{{$thread->replies_count}}</small>
			<small><i class="fas fa-eye thread-meta-icon"></i>{{ $thread->visits }}</small>
		</div>

		<div class="col-9 level text-right">
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
