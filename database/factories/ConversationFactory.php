<?php

use Faker\Generator as Faker;

$factory->define(App\Conversation::class, function (Faker $faker) {
    return [
        'channel_name' => $faker->word
    ];
});
