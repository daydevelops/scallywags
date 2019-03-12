<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;

class ThreadReplyCreated
{
    use Dispatchable, SerializesModels;
	public $reply;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($reply)
    {
        $this->reply = $reply;
    }
}
