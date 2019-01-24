<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadsTest extends TestCase
{
	use DatabaseMigrations;

	public function setup() {
		parent::setup();

		$this->thread = factory('App\Thread')->create();
		$this->replies = factory('App\ThreadReply',10)->create(['thread_id'=>$this->thread->id]);
	}

	 /** @test */
    public function a_user_can_browse_threads()
    {
        $response = $this->get('/forum');
        $response->assertSee($this->thread->title);

    }

	/** @test */
	public function a_user_can_view_a_thread()
	{
	 	$response = $this->get('/forum/'.$this->thread->id);
		$response->assertSee($this->thread->title);
	}

	/** @test */
	public function a_user_can_see_replies_to_a_thread() {
		// given we have a thread with replies
		// when we visit the thread
		$response = $this->get('/forum/'.$this->thread->id);
		// we should see the replies
		$response->assertSee($this->replies[0]->body);
	}
}
