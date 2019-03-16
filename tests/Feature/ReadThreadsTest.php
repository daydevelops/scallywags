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
		$response = $this->get($this->thread->getPath());
		$response->assertSee($this->thread->title);
	}

	/** @test */
	public function a_user_can_see_replies_to_a_thread() {
		$this->signOut();
		// given we have a thread with replies
		// when we visit the thread
		$response = $this->get($this->thread->getPath()."/replies");
		// we should see the replies
		$response->assertSee($this->replies[0]->body);
	}

	/** @test*/
	public function an_unauthenticated_user_cannot_access_the_thread_create_page() {
		$this->withExceptionHandling()->get('forum/new')->assertRedirect('/login');
		$this->withExceptionHandling()->post('forum')->assertRedirect('/login');
	}

	/** @test */
	public function a_user_can_browse_thread_by_category() {
		$cat = factory('App\Category')->create();
		$thread_in_cat = factory('App\Thread')->create(['category_id'=>$cat->id]);
		$thread_not_in_cat = factory('App\Thread')->create();

		$this->get('forum/'.$cat->slug)
		->assertSee($thread_in_cat->title)
		->assertDontSee($thread_not_in_cat->title);

	}

	/** @test */
	public function a_user_can_filter_threads_by_user() {
		// given that we have 2 threads from different users
		// when trying to view only threads for user1
		// then user1 thread should be visible
		// and user2 thread should not be visible
		$threads = factory('App\Thread',2)->create();

		$this->get('forum?u='.$threads[0]->user_id)
		->assertSee($threads[0]->title)
		->assertDontSee($threads[1]->title);
	}

	/** @test */
	public function a_user_can_sort_threads_by_popularity() {
		// given that we have many threads with random amount of replies
		$t1 = factory('App\Thread')->create();
		$t2 = factory('App\Thread')->create();
		$t3 = $this->thread;

		factory('App\ThreadReply',2)->create(['thread_id'=>$t1->id]);
		factory('App\ThreadReply',3)->create(['thread_id'=>$t2->id]);

		// when we sort those threads by the number of replies
		$response = $this->getJson('/forum?popular=1')->json();
		// dd($response);
		// the user should see the threads sorted
		$this->assertEquals([10,3,2],array_column($response['data'],'replies_count'));
	}

	/** @test */
	public function a_user_can_sort_threads_by_unanswered() {
		// given that we have many threads with random amount of replies
		$t1 = factory('App\Thread')->create();
		$t2 = factory('App\Thread')->create();

		factory('App\ThreadReply',2)->create(['thread_id'=>$t1->id]);
		// when we sort those threads by the number of replies
		$response = $this->get('/forum?unanswered=1');
		// dd($response);
		// the user should see the threads sorted
		$response->assertSee($t2->body);
		$response->assertDontSee($t1->body);
	}

	/** @test */
	public function it_logs_a_visit() {
		$thread = factory('App\Thread')->create();
		$this->assertEquals(0,$thread->visits);
		$this->call('GET',$thread->getPath());
		$this->assertEquals(1,$thread->refresh()->visits);
	}

}
