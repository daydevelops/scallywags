<?php

use Faker\Generator as Faker;

$factory->define(App\Message::class, function (Faker $faker) {
    return [
        'user_id' => function() {
			return factory('App\User')->create()->id;
		},
		'chat_id' => function() {
			return factory('App\Chat')->create()->id;
        },
        'body' => $faker->paragraph
    ];
});
