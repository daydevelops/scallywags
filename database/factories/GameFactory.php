<?php

use Faker\Generator as Faker;

$factory->define(App\Game::class, function (Faker $faker) {
    $randDay = rand(1,3)*60*60*24;
    $randHour = rand(-10,10)*60*60; // converted to seconds
    $randtime = date('c',time()+$randDay+$randHour);
    return [
        'gamedate'=>$randtime,
        'private'=>rand(0,1),
        'admin-created'=>rand(0,1),
        'event_id'=>1
    ];
});
