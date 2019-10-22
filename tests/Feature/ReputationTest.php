<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use App\ThreadReply;


class ReputationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_earns_rep_when_creating_a_thread()
    {
        $this->signIn();
        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);
        $this->assertEquals(1, $thread->user->reputation);
    }

    /** @test */
    public function a_user_loses_rep_when_deleting_their_thread()
    {
        $this->signIn();
        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);
        $thread->delete();
        $this->assertEquals(0, $thread->user->reputation);
    }

    /** @test */
    public function a_user_earns_rep_when_their_thread_gets_a_reply()
    {
        $this->signIn();
        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);
        $reply = factory(ThreadReply::class)->make(['user_id' => 999])->toArray();
        $thread->addReply($reply);
        $this->assertEquals(2, $thread->user->fresh()->reputation);
    }

    /** @test */
    public function a_user_loses_rep_when_a_reply_to_their_thread_is_deleted()
    {
        $this->signIn();
        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);
        $reply = factory(ThreadReply::class)->make(['user_id' => 999])->toArray();
        $thread->addReply($reply);
        ThreadReply::first()->delete();
        $this->assertEquals(1, $thread->user->fresh()->reputation);
    }

    /** @test */
    public function a_user_earns_rep_when_creating_a_reply()
    {
        $this->signIn();
        $reply = factory(ThreadReply::class)->make(['user_id' => auth()->id()]);
        Thread::first()->addReply($reply->toArray());
        $this->assertEquals(1,auth()->user()->fresh()->reputation);
    }

    /** @test */
    public function a_user_loses_rep_when_deleting_their_reply()
    {
        $this->signIn();
        $reply = factory(ThreadReply::class)->make(['user_id' => auth()->id()]);
        Thread::first()->addReply($reply->toArray());
        ThreadReply::first()->delete();
        $this->assertEquals(0,auth()->user()->fresh()->reputation);
    }

    /** @test */
    public function a_user_earns_rep_when_their_reply_is_favourited()
    {
        $this->signIn();
        $thread = factory(Thread::class)->create(['user_id' => 999]);
        $reply = factory(ThreadReply::class)->make(['user_id' => auth()->id()]);
        $thread->addReply($reply->toArray());
        $reply = ThreadReply::first();

        $this->signIn();
        $response = $this->post('favourite/reply/' . $reply->id);
        $this->assertEquals(1 + 3, $reply->user->fresh()->reputation);
    }

    /** @test */
    public function a_user_loses_rep_when_their_reply_is_unfavourited() {
        $this->signIn();
        $thread = factory(Thread::class)->create(['user_id' => 999]);
        $reply = factory(ThreadReply::class)->make(['user_id' => auth()->id()]);
        $thread->addReply($reply->toArray());
        $reply = ThreadReply::first();

        $this->signIn();
        $response = $this->post('favourite/reply/' . $reply->id);
        $response = $this->delete('favourite/reply/' . $reply->id);
        $this->assertEquals(1, $reply->user->fresh()->reputation);
    }

    /** @test */
    public function a_user_earns_rep_when_their_thread_is_favourited()
    {
        $this->signIn();
        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $this->signIn();
		$response = $this->post('favourite/thread/'.$thread->slug);
        $this->assertEquals(1 + 3, $thread->user->fresh()->reputation);
    }

    /** @test */
    public function a_user_loses_rep_when_their_thread_is_unfavourited()
    {
        $this->signIn();
        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $this->signIn();
		$response = $this->post('favourite/thread/'.$thread->slug);
		$response = $this->delete('favourite/thread/'.$thread->slug);
        $this->assertEquals(1, $thread->user->fresh()->reputation);
    }

    /** @test */
    public function a_user_earns_rep_when_their_reply_is_bested() {
		$this->signIn();
		$thread = factory('App\Thread')->create(['user_id'=>auth()->id()]);
		$reply = factory('App\ThreadReply')->create(['thread_id'=>$thread->id]);
		$this->post('/forum/reply/'.$reply->id.'/best');
        $this->assertEquals(10, $reply->user->fresh()->reputation);
    }

    /** @test */
    public function a_user_does_not_earn_rep_for_favouriting_their_own_reply() {
        $this->signIn();
        $thread = factory(Thread::class)->create(['user_id' => 999]);
        $reply = factory(ThreadReply::class)->make(['user_id' => auth()->id()]);
        $thread->addReply($reply->toArray());
        $reply = ThreadReply::first();

        $response = $this->post('favourite/reply/' . $reply->id);
        $this->assertEquals(1, $reply->user->fresh()->reputation);
    }

    /** @test */
    public function a_user_does_not_lose_rep_for_unfavouriting_their_own_reply() {
        $this->signIn();
        $thread = factory(Thread::class)->create(['user_id' => 999]);
        $reply = factory(ThreadReply::class)->make(['user_id' => auth()->id()]);
        $thread->addReply($reply->toArray());
        $reply = ThreadReply::first();

        $response = $this->post('favourite/reply/' . $reply->id);
        $response = $this->delete('favourite/reply/' . $reply->id);
        $this->assertEquals(1, $reply->user->fresh()->reputation);
    }


    /** @test */
    public function a_user_does_not_earn_rep_for_favouriting_their_own_thread()
    {
        $this->signIn();
        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

		$response = $this->post('favourite/thread/'.$thread->slug);
        $this->assertEquals(1, $thread->user->fresh()->reputation);
    }

    /** @test */
    public function a_user_does_not_lose_rep_for_unfavouriting_their_own_thread()
    {
        $this->signIn();
        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

		$response = $this->post('favourite/thread/'.$thread->slug);
		$response = $this->delete('favourite/thread/'.$thread->slug);
        $this->assertEquals(1, $thread->user->fresh()->reputation);
    }

    /** @test */
    public function a_user_does_not_earn_rep_for_besting_their_own_reply() {
        $this->signIn();
		$thread = factory('App\Thread')->create(['user_id'=>auth()->id()]);
        $reply = factory('App\ThreadReply')->create([
            'thread_id'=>$thread->id,
            'user_id'=>auth()->id()
        ]);
		$this->post('/forum/reply/'.$reply->id.'/best');
        $this->assertEquals(1, $reply->user->fresh()->reputation);
    }

    public function publishThread($overrides = [])
    {
        $this->signIn();
        $thread = factory('App\Thread')->make($overrides);
        $thread = $thread->toArray();
        $thread['g-recaptcha-response'] = 'test';
        return $this->withExceptionHandling()->post('/forum/', $thread);
    }

    public function publishReply($overrides = [])
    {
        $this->signIn();
        $thread = factory('App\Thread')->create();
        $reply = factory('App\ThreadReply')->make($overrides);
        return $this->withExceptionHandling()->post($thread->getPath() . '/reply/', $reply->toArray());
    }
}
