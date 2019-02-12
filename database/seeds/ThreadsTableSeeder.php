<?php

use Illuminate\Database\Seeder;
use App\Thread;
use App\ThreadReply;
class ThreadsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$admin_thread = factory('App\Thread')->create(['user_id'=>1]);
		factory('App\ThreadReply',10)->create(['thread_id'=>$admin_thread->id]);

		$threads = factory('App\Thread',5)->create();
		$threads->each(function ($thread) {
			factory('App\ThreadReply',5)->create(['thread_id'=>$thread->id]);
			factory('App\ThreadReply',1)->create([
				'thread_id'=>$thread->id,
				'user_id'=>1
			]);
		});
    }
}
