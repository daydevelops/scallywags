<?php

namespace App\Listeners;

use App\Events\NewMessage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyMessagedUsers
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
     * @param  NewMessage  $event
     * @return void
     */
    public function handle(NewMessage $event)
    {
        $message = $event->data;
        foreach($message->chat->users as $user) {
			if ($user->id != auth()->id()) { //no need to notify the user leaving the message
                $user->notify(new \App\Notifications\NewMessage($message));
			}
		}
    }
}
