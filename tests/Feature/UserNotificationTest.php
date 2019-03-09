<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Notifications\Notification;

class UserNotificationTest extends TestCase
{
	use DatabaseMigrations;

	public function setup() {
		parent::setup();
	}

	/** @test */
	public function an_auth_user_can_view_their_unread_notifications()
	{
		$note = $this->addNotification();
		$response = $this->get('/notifications');
		$response->assertSee($note->data['description']);
	}

	/** @test */
	public function an_auth_user_can_mark_notifications_as_read()
	{
		$note = $this->addNotification();
		$response = $this->delete('/notifications/all');
		$this->assertTrue($note->refresh()->read_at !== null);
	}

	/** @test */
	public function an_auth_user_can_mark_single_notifications_as_read()
	{
		$note = $this->addNotification();
		$response = $this->delete('/notifications/'.$note->id);
		$this->assertTrue($note->refresh()->read_at !== null);
	}

	// /** @test */
	// public function an_auth_does_not_see_prev_read_notifications()
	// {
	// 	$note = $this->addNotification();
	//
	// 	$response = $this->get('/');
	// 	$response->assertDontSee($note->data['description']);
	// }

	public function addNotification() {
		$this->signIn();
		$thread = factory('App\Thread')->create();
		$thread->subscribe();
		$thread->addReply([
			'user_id'=>factory('App\User')->create()->id,
			'body' => 'lorem ipsum'
		]);
		return auth()->user()->notifications()->latest()->first();
	}

}
