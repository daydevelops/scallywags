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
                // first check if there is already a notification for this chat
                $data_for_notif = [
                    'chat_id'=>$message->chat_id,
                    'description'=>$message->user->name." has sent you a new message!",
                    'url'=>"/chats"
                ];
                $count = $user->unreadNotifications()->where([
                    'type'=>'App\Notifications\NewMessage',
                    'data'=>json_encode($data_for_notif)
                ])->count(); 
                if ($count == 0) {
                    $user->notify(new \App\Notifications\NewMessage($message,$data_for_notif));
                }
			}
		}
    }
}
