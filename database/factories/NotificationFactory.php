<?php

use Faker\Generator as Faker;

$factory->define(\Illuminate\Notifications\DatabaseNotification::class, function ($faker) {
	// $user = factory('App\User')->create();
	$thread = factory('App\Thread')->create(['user_id'=>999]);
    return [
        'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
        'type' => 'App\Notifications\ThreadRepliedTo',
        'notifiable_id' => function () {
            return auth()->id() ?: factory('App\User')->create()->id;
        },
        'notifiable_type' => 'App\User',
        'data' => [
            'thread_id'=>$thread->id,
			'description'=>"New reply to thread: ".$thread->title,
			'url'=>$thread->getPath()
		]
    ];
});
