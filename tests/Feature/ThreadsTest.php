<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadsTest extends TestCase
{
	use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
	 /** @test */
    public function a_user_can_browse_threads()
    {
		$thread = factory('App\Thread')->create();

        $response = $this->get('/forum');
        $response->assertSee($thread->title);

    }

	/** @test */
	public function a_user_can_view_a_thread()
	{
	 	$thread = factory('App\Thread')->create();

	 	$response = $this->get('/forum/'.$thread->id);
		$response->assertSee($thread->title);
	}
}
