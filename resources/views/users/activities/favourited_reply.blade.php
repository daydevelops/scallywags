<?php $thread = $f->thread; ?>
<div class="panel">
	<div class="row header">
		<div class="col-12 text-left">
			<p><small><em>
				<span class='subject-action'>favourited a reply to </span>
				<span class='thread-replied-to'><a href='{{$thread->getPath()."#reply-".$f->id}}'>{{$thread->title}} </a></span>
				<span class='subject-date'>{{$act->created_at->diffForHumans()}}</span>
			</em></small></p>
		</div>
	</div>
</div>
