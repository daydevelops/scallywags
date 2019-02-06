<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'=>'Adam Day',
            'email'=>'adamday1618@gmail.com',
            'password'=>Hash::make('adam'),
            'email_verified_at' => now(),
            'skill' => 'A',
            'image' => 'NA',
			'is_admin'=>1
        ]);
        factory(App\User::class,10)->create();
    }
}
