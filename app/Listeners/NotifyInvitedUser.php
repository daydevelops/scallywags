<?php

namespace App\Listeners;

use App\Events\InvitedToGame;
use App\Mail\GameInvite;
use App\Notifications\InvitedToGame as SendNotifications;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyInvitedUser
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
     * @param  InvitedToGame  $event
     * @return void
     */
    public function handle(InvitedToGame $event)
    {
		// \Mail::to($event->user->email)->send(
		// 	new GameInvite($event->user,$event->game)
		// );
		$event->user->notify(new SendNotifications($event));
    }
}
