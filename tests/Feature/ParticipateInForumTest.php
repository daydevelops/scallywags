<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function an_authenticated_user_can_reply_to_a_thread()
	{
		// given we have an authenticated user,
		$this->signIn();

		// and we have a thread they can read
		$thread = factory('App\Thread')->create();

		// when the user replies to the thread
		$reply = factory('App\ThreadReply')->make();
		$this->post($thread->getPath().'/reply',$reply->toArray());

		$this->assertInstanceOf('App\ThreadReply',$thread->replies()->first());
		// the reply should be visible on the thread page
		$response = $this->get($thread->getPath());
		$response->assertSee($reply->body);
	}

	/** @test */
	public function an_unauthenticated_user_can_not_reply_to_a_thread()
	{
		$this->expectException('Illuminate\Auth\AuthenticationException');
		$this->post('/forum/foobar/1/reply',[]);
	}

	/** @test*/
	public function an_authenticated_user_can_create_a_thread() {
		// given we have a user who is signed in
		$this->signIn();

		// when a user submits a post request to add a thread
		$thread = factory('App\Thread')->make();
		$this->post('/forum/',$thread->toArray());

		// the reply should be visible on the thread page
		$response = $this->get('/forum/');
		$response->assertSee($thread->title);
		$response->assertSee($thread->body);
	}

	/** @test */
	public function a_thread_requires_a_title() {
		$this->publishThread(['title'=>null])->assertSessionHasErrors('title');
	}
	/** @test */
	public function a_thread_requires_a_body() {
		$this->publishThread(['body'=>null])->assertSessionHasErrors('body');
	}
	/** @test */
	public function a_thread_requires_a_valid_category() {
		factory('App\Category',5)->create();
		$this->publishThread(['category_id'=>null])->assertSessionHasErrors('category_id');
		$this->publishThread(['category_id'=>999999])->assertSessionHasErrors('category_id');
	}

	/** @test */
	public function a_reply_requires_a_body() {
		$this->publishReply(['body'=>null])->assertSessionHasErrors('body');
	}

	/** @test */
	public function a_user_can_delete_their_thread() {
		$this->signIn();
		$thread = factory('App\Thread')->create(['user_id'=>auth()->id()]);
		$reply = factory('App\ThreadReply')->create(['thread_id'=>$thread->id]);
		$this->json('DELETE',$thread->getPath());
		$this->assertDatabaseMissing('threads',['id'=>$thread->id]);
		$this->assertDatabaseMissing('thread_replies',['id'=>$reply->id]);
	}
	/** @test */
	public function a_user_can_delete_their_reply() {
		$this->signIn();
		$reply = factory('App\ThreadReply')->create(['user_id'=>auth()->id()]);
		// dd($reply);
		$this->json('DELETE','/forum/reply/'.$reply->id);
		$this->assertDatabaseHas('thread_replies',['id'=>$reply->id,'body'=>'Reply deleted']);
	}

	/** @test */
	public function a_guest_can_not_delete_a_thread() {
		$this->withExceptionHandling()->json('DELETE','/forum/foobar/1')->assertStatus(401);
	}
	/** @test */
	public function a_guest_can_not_delete_a_reply() {
		$this->withExceptionHandling()->json('DELETE','/forum/reply/1')->assertStatus(401);
	}

	/** @test */
	public function a_user_can_not_delete_another_users_thread() {
		$this->signIn();
		$thread = factory('App\Thread')->create(['user_id'=>auth()->id()+1]);
		$this->withExceptionHandling()->json('DELETE',$thread->getPath())->assertStatus(403);
		$this->assertDatabasehas('threads',['id'=>$thread->id]);
	}

	/** @test */
	public function a_user_can_not_delete_another_users_reply() {
		$this->signIn();
		$reply = factory('App\ThreadReply')->create(['user_id'=>auth()->id()+1]);
		$this->withExceptionHandling()->json('DELETE','/forum/reply/'.$reply->id);
		$this->assertDatabaseMissing('thread_replies',['id'=>$reply->id,'body'=>'Reply deleted']);
	}









	public function publishThread($overrides=[]) {
		$this->signIn();
		$thread = factory('App\Thread')->make($overrides);
		return $this->withExceptionHandling()->post('/forum/',$thread->toArray());
	}
	public function publishReply($overrides=[]) {
		$this->signIn();
		$thread = factory('App\Thread')->create();
		$reply = factory('App\ThreadReply')->make($overrides);
		return $this->withExceptionHandling()->post($thread->getPath().'/reply/',$reply->toArray());
	}


}
