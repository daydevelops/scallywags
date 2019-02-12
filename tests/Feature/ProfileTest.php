<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfileTest extends TestCase
{
	use DatabaseMigrations;

	public function setup() {
		parent::setup();
	}

	/** @test */
	public function an_auth_user_can_view_a_users_profile()
	{
		$this->signIn();
		$user = factory('App\User')->create();
		$response = $this->get('/profile/'.$user->id);
		$response->assertSee($user->name);
	}

	/** @test */
	public function an_unauth_user_cannot_view_a_users_profile()
	{
		$this->expectException('Illuminate\Auth\AuthenticationException');
		$user = factory('App\User')->create();
		$response = $this->get('/profile/'.$user->id);
	}

	/** @test */
	public function a_profile_contains_the_users_forum_activity() {
		$this->signIn();

		$threads = factory('App\Thread',2)->create();
		$reply = factory('App\ThreadReply')->create([
			'user_id'=>$threads[0]->user_id,
			'thread_id'=>$threads[1]->id
		]);

		$this->get('profile/'.$threads[0]->user_id)
		->assertSee($threads[0]->title)
		->assertDontSee($threads[1]->body);
	}


}
