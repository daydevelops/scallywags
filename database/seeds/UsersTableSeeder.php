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
			$hash = md5(strtolower(trim(env('ADMIN_EMAIL'))));
			$image = "https://www.gravatar.com/avatar/$hash?d=wavatar";
			User::create([
				'name'=>env('ADMIN_NAME'),
				'email'=>env('ADMIN_EMAIL'),
				'password'=>Hash::make(env('ADMIN_PASS')),
				'email_verified_at' => now(),
				'skill' => 'A',
				'is_admin'=>1,
				'image'=>$image
			]);
		}
		factory(App\User::class,10)->create();
	}
}
