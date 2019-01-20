<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class GameInvite extends Mailable
{
    use Queueable, SerializesModels;
	public $user;
	public $game;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$game)
    {
		$this->user = $user;
        $this->game = $game;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.invited-to-game');
    }
}
