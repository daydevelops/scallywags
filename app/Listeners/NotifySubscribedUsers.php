<?php

namespace App\Listeners;

use App\Events\ThreadReplyCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifySubscribedUsers
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
		foreach($event->reply->thread->subscriptions as $sub) {
			if ($event->reply->user_id !== auth()->id()) { //no need to notify the user leaving the reply
				$sub->user->notify(new \App\Notifications\ThreadRepliedTo($event->reply));
			}
		}
    }
}
