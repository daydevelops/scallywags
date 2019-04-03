<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LockedThreadsTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function an_admin_can_lock_and_unlock_a_thread() {
		$thread = factory('App\Thread')->create();
		$this->signIn(factory('App\User')->create(['is_admin'=>1]));
		$this->post($thread->getPath().'/lock');
		$this->assertTrue($thread->fresh()->is_locked == 1);
		$this->post($thread->getPath().'/unlock');
		$this->assertTrue($thread->fresh()->is_locked == 0);
	}

	/** @test */
	public function a_user_cannot_reply_to_a_locked_thread() {
		$this->signIn();
		$thread = factory('App\Thread')->create();
		$thread->lock();
		$reply = factory('App\ThreadReply')->make();
		$this->json('post',$thread->getPath().'/reply',$reply->toArray())->assertStatus(422);
		$this->assertDatabaseMissing('thread_replies',['body'=>$reply->body]);
	}

	/** @test */
	public function only_admin_can_lock_or_unlock_a_thread() {
		$thread = factory('App\Thread')->create();
		$this->signIn(factory('App\User')->create(['is_admin'=>0]));
		$this->post($thread->getPath().'/lock')->assertRedirect('/login');
		$this->post($thread->getPath().'/unlock')->assertRedirect('/login');
	}

}
