<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MentioningUsersTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function a_user_is_notified_when_they_are_mentioned() {
		$this->signIn();
		$user = factory('App\User')->create(['name'=>'bob']);
		// $user = factory('App\User')->create(['name'=>'alice']);
		$thread = factory('App\Thread')->create();
		$reply = factory('App\ThreadReply')->make(['body'=>"hello @bob and @alice "]);
		$this->post($thread->getPath().'/reply',$reply->toArray());

		$this->assertDatabaseHas('notifications',[
			'notifiable_type'=>'App\User',
			'type'=>'App\Notifications\UserMentioned',
			'notifiable_id'=>$user->id
		]);
	}
}
