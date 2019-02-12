<?php $r = $act->subject; ?>
<div class="panel">
	<div class="row header">
		<div class="col-8 text-left">
			<p><small><em>
				<span class='subject-action'>replied to a thread </span>
				<span class='thread-replied-to'><a href='{{$r->thread->getPath()}}'>{{$r->thread->title}} </a></span>
				<span class='subject-date'>{{$r->created_at->diffForHumans()}}</span>
			</em></small></p>
		</div>
		<div class="col-4 text-right">
			@can('favourite',$r)
				<p id ='thread-{{$r->id}}' class='favourite-wrapper {{$r->isFavourited()?'favourited':' '}}'><i class="fas fa-heart" onclick='toggleFavourite("thread",{{$r->id}})'></i></p>
			@endcan
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-12">
			<p class='thread-body'>{{$r->body}}</p>
		</div>
	</div>
</div>
