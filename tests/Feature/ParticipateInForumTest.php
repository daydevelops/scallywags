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
		$user = factory('App\User')->create();
		$this->be($user);

		// and we have a thread they can read
		$thread = factory('App\Thread')->create();

		// when the user replies to the thread
		$reply = factory('App\ThreadReply')->make();
		$this->post('/forum/'.$thread->id.'/reply',$reply->toArray());

		$this->assertInstanceOf('App\ThreadReply',$thread->replies()->first());
		// the reply should be visible on the thread page
		$response = $this->get('/forum/'.$thread->id);
		// $response->assertSee($thread->title);
		$response->assertSee($reply->body);
    }

	/** @test */
	public function an_unauthenticated_user_can_not_reply_to_a_thread()
	{
		$this->expectException('Illuminate\Auth\AuthenticationException');
		$this->post('/forum/1/reply',[]);
	}
}
