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
		if (!User::find(1)) {
			User::create([
				'name'=>'Adam Day',
				'email'=>'adamday1618@gmail.com',
				'password'=>Hash::make('adam'),
				'email_verified_at' => now(),
				'skill' => 'A',
				'is_admin'=>1
			]);
		}
		factory(App\User::class,10)->create();
	}
}
