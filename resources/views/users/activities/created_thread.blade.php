<?php $t = $act->subject; ?>
<div class="panel">
	<div class="row header">
		<div class="col-8 text-left">
			<p><small><em>
				<span class='subject-action'>created a thread </span>
				<span class='subject-date'>{{$t->created_at->diffForHumans()}}</span>
			</em></small></p>
			<p class='thread-title'><a href='{{$t->getPath()}}'>{{$t->title}}</a></p>
		</div>
		<div class="col-4 text-right">
			<p class='thread-reply-count'><small><em>{{$t->replies_count}} {{str_plural('comment',$t->replies_count)}}</em></small></p>
			@can('favourite',$t)
				<p id ='thread-{{$t->id}}' class='favourite-wrapper {{$t->isFavourited()?'favourited':' '}}'><i class="fas fa-heart" onclick='toggleFavourite("thread",{{$t->id}})'></i></p>
			@endcan
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-12">
			<p class='thread-body'>{{$t->body}}</p>
		</div>
	</div>
</div>
