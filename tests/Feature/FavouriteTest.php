<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavouriteTest extends TestCase
{
	use DatabaseMigrations;

	// public function setup() {
	// 	// parent::setup();
	// }

	/** @test */
	public function a_guest_cannot_favourite_anything() {
		$this->withExceptionHandling()->post('/favourite/thread/1')->assertRedirect('/login');
	}

	/** @test */
	public function a_user_can_favourite_a_thread() {
		// given that we have an authenticated user and a thread
		$this->signIn();
		$thread = factory('App\Thread')->create();
		// when the user sends a request to "favourite" that thread
		$response = $this->post('favourite/thread/'.$thread->id);
		// dd(\App\Favourite::all());
		// the thread should have one favourite count
		$this->assertCount(1,$thread->favourites);
	}

	/** @test */
	public function a_user_can_unfavourite_a_thread() {
		// given that we have an authenticated user and a thread
		$this->signIn();
		$thread = factory('App\Thread')->create();
		// when the user sends a request to "favourite" that thread
		$response = $this->post('favourite/thread/'.$thread->id);
		$response = $this->post('unfavourite/thread/'.$thread->id);
		$this->assertCount(0,\App\Thread::find($thread->id)->favourites);
	}

	/** @test */
	public function a_user_can_favourite_a_reply() {
		// given that we have an authenticated user and a reply
		$this->signIn();
		$reply = factory('App\ThreadReply')->create();
		// when the user sends a request to "favourite" that reply
		$response = $this->post('favourite/reply/'.$reply->id);
		// dd(\App\Favourite::all());
		// the reply should have one favourite count
		$this->assertCount(1,$reply->favourites);
	}

	/** @test */
	public function a_user_can_unfavourite_a_reply() {
		// given that we have an authenticated user and a thread
		$this->signIn();
		$reply = factory('App\ThreadReply')->create();
		// when the user sends a request to "favourite" that thread
		$response = $this->post('favourite/reply/'.$reply->id);
		$response = $this->post('unfavourite/reply/'.$reply->id);
		$this->assertCount(0,\App\ThreadReply::find($reply->id)->favourites);
	}

	/** @test */
	public function an_auth_user_can_only_favourite_an_object_once() {
		$this->signin();
		$thread = factory('App\Thread')->create();
		$reply = factory('App\ThreadReply')->create(['thread_id'=>$thread->id]);

		$response = $this->post('favourite/reply/'.$reply->id);
		$response = $this->post('favourite/reply/'.$reply->id);//->assertSee('You have already favourited this reply');
		$this->assertCount(1,$reply->favourites);

		$response = $this->post('favourite/thread/'.$thread->id);
		$response = $this->post('favourite/thread/'.$thread->id);//->assertSee('You have already favourited this reply');
		$this->assertCount(1,$thread->favourites);
	}

	/** @test */
	public function a_favourite_records_activity() {
		$this->signin();
		$thread = factory('App\Thread')->create();
		$thread->favourite();
		$this->assertDatabaseHas('forum_activities',[
			'subject_id'=>$thread->favourites[0]->id,
			'subject_type'=>get_class($thread->favourites[0])
		]);
	}

}
