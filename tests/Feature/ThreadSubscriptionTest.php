<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadSubscriptionTest extends TestCase
{
	use DatabaseMigrations;

	// public function setup() {
	// 	// parent::setup();
	// }

	/** @test */
	public function a_guest_cannot_subscribe_to_a_thread() {
		$this->expectException('Illuminate\Auth\AuthenticationException');
		$this->post('/forum/foobar/1/subscribe');
	}

	/** @test */
	public function a_user_can_subscribe_and_unsubscribe_to_a_thread() {
		$this->signIn();
		$thread = factory('App\Thread')->create(['user_id'=>999]);
		$request = $this->post($thread->getPath().'/subscribe');
		$this->assertTrue(auth()->id() == $thread->subscriptions[0]->user_id);
		$request = $this->delete($thread->getPath().'/unsubscribe');
		$this->assertCount(0,$thread->refresh()->subscriptions);
	}

	/** @test */
	public function a_user_is_auto_subscribed_to_their_thread() {
		$this->signIn();
		$thread = factory('App\Thread')->make(['user_id'=>auth()->id()]);
		$this->post('/forum/',$thread->toArray());
		$thread = \App\Thread::first();
		$this->assertTrue(auth()->id() == $thread->subscriptions[0]->user_id);
	}

	/** @test */
	public function a_user_can_only_subscribe_to_a_thread_once() {
		$this->signIn();
		$thread = factory('App\Thread')->create(['user_id'=>999]);
		$request = $this->post($thread->getPath().'/subscribe');
		$request = $this->post($thread->getPath().'/subscribe');
		$this->assertCount(1,\App\ThreadSubscription::all());
	}

	/** @test */
	public function users_are_notified_when_a_subscribed_thread_receives_a_reply() {
		$this->signIn();
		$thread = factory('App\Thread')->create(['user_id'=>999]);
		$thread->subscribe();
		$reply = factory('App\ThreadReply')->make();
		$thread->addReply($reply->toArray());
		$this->assertDatabaseHas('notifications',[
			'notifiable_type'=>'App\User',
			'type'=>'App\Notifications\ThreadRepliedTo',
			'notifiable_id'=>auth()->id()
		]);
	}

	/** @test */
	public function users_are_not_notified_when_leaving_their_own_reply() {
		$this->signIn();
		$thread = factory('App\Thread')->create(['user_id'=>999]);
		$thread->subscribe();
		$reply = factory('App\ThreadReply')->make(['user_id'=>auth()->id()]);
		$thread->addReply($reply->toArray());
		$this->assertDatabaseMissing('notifications',[
			'notifiable_type'=>'App\User',
			'type'=>'App\Notifications\ThreadRepliedTo',
			'notifiable_id'=>auth()->id()
		]);
	}

}
