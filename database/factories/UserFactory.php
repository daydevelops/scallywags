<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    $email = $faker->unique()->safeEmail;
    $hash = md5(strtolower(trim($email)));
    $image = "https://www.gravatar.com/avatar/$hash?d=wavatar";
    
    return [
        'name' => $faker->unique()->name,
        'email' => $email,
        'email_verified_at' => now(),
        'skill' => 'A',
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'is_admin'=>0,
        'image'=>$image
    ];
});
