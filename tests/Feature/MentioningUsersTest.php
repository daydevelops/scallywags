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

	/** @test */
	public function user_names_can_be_fetched_for_mention_autocomplete() {
		factory('App\User')->create(['name'=>'adamday']);
		factory('App\User')->create(['name'=>'johndoe']);
		$response = $this->json('GET','/api/users',['name'=>'adam']);
		$this->assertContains('adamday',json_encode($response));
		$this->assertNotContains('johndoe',json_encode($response));
	}
}
