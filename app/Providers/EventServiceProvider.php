<?php

namespace App\Providers;

use App\Events\ThreadReplyCreated;
use App\Events\NewMessage;
use App\Listeners\NotifyInvitedUser;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
		ThreadReplyCreated::class => [
			\App\Listeners\NotifySubscribedUsers::class,
			\App\Listeners\NotifyMentionedUsers::class
        ],
		NewMessage::class => [
			\App\Listeners\NotifyMessagedUsers::class
		]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
