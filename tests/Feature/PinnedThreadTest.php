<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PinnedThreadTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function only_admin_can_see_the_pin_btn()
	{
		$this->signIn();
		$thread = factory('App\Thread')->create();
		$response = $this->get($thread->getPath());
		$response->assertDontSee('id="pin-btn"');
		$this->signIn(factory('App\User')->create(['is_admin' => 1]));
		$response = $this->get($thread->getPath());
		$response->assertSee('id="pin-btn"');
	}

	/** @test */
	public function the_admin_can_pin_a_thread() {
		$thread = factory('App\Thread')->create(['is_pinned'=>0]);
		$this->signIn(factory('App\User')->create(['is_admin' => 1]));
		$this->post($thread->getPath()."/pin");
		$this->assertTrue(!!$thread->fresh()->is_pinned);
	}

	/** @test */
	public function the_admin_can_unpin_a_thread() {
		$thread = factory('App\Thread')->create(['is_pinned'=>1]);
		$this->signIn(factory('App\User')->create(['is_admin' => 1]));
		$this->post($thread->getPath()."/unpin");
		$this->assertTrue(!$thread->fresh()->is_pinned);
	}

	/** @test */
	public function a_non_admin_cannot_pin_a_thread() {
		$thread = factory('App\Thread')->create(['is_pinned'=>0]);
		$this->signIn(factory('App\User')->create(['is_admin' => 0]));
		$this->withExceptionHandling()->post($thread->getPath()."/pin");
		$this->assertTrue(!$thread->fresh()->is_pinned);
	}

	/** @test */
	public function a_non_admin_cannot_unpin_a_thread() {
		$thread = factory('App\Thread')->create(['is_pinned'=>1]);
		$this->signIn(factory('App\User')->create(['is_admin' => 0]));
		$this->withExceptionHandling()->post($thread->getPath()."/unpin");
		$this->assertTrue(!!$thread->fresh()->is_pinned);
	}

	/** @test */
	public function a_pinned_thread_appears_at_the_top_of_threads() {
		
		$t1 = factory('App\Thread')->create(['is_pinned'=>0]);
		$t2 = factory('App\Thread')->create(['is_pinned'=>1]);
		$t3 = factory('App\Thread')->create(['is_pinned'=>0]);

		$response = $this->get('/')->getContent();
		// dd($response->getContent());

		$t1_pos = strpos($response,$t1->title);
		$t2_pos = strpos($response,$t2->title);
		$t3_pos = strpos($response,$t3->title);

		$this->assertTrue($t2_pos < $t1_pos);
		$this->assertTrue($t2_pos < $t3_pos);
	}
}
