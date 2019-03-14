<?php

use Illuminate\Database\Seeder;
class NotificationsTableSeeder extends Seeder
{
	/**
	* Run the database seeds.
	*
	* @return void
	*/
	public function run()
	{
		factory('Illuminate\Notifications\DatabaseNotification')->create(['notifiable_id'=>1]);
	}
}
