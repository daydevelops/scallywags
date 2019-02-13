<?php
$f = $act->subject->favourited; ?>
@if (class_basename($f) == 'Thread')
	@include('users/activities/favourited_thread')
@else
	@include('users/activities/favourited_reply')
@endif
