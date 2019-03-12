<?php

namespace App\Listeners;

use App\Events\ThreadReplyCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyMentionedUsers
{
	/**
	* Create the event listener.
	*
	* @return void
	*/
	public function __construct()
	{
		//
	}

	/**
	* Handle the event.
	*
	* @param  ThreadReplyCreated  $event
	* @return void
	*/
	public function handle(ThreadReplyCreated $event)
	{
		$reply = $event->reply;
		// check for any mentioned users
		preg_match_all('/\@([^\s\.]+)/',$reply->body,$matches);
		$names = $matches[1];
		// for each user, send notification
		foreach ($names as $n) {
			$user = \App\User::where(['name'=>$n])->first();
			if ($user) {
				$user->notify(new \App\Notifications\UserMentioned($reply));
			}
		}
	}
}
