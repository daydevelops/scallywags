<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Carbon\Carbon;

class ThreadTest extends TestCase
{
	use DatabaseMigrations;

	public function setup() {
		parent::setup();

		$this->thread = factory('App\Thread')->create();
	}

    /** @test */
    public function it_has_a_user()
    {
		$this->assertInstanceOf('App\User',$this->thread->user);
    }
	/** @test */
	public function it_owns_a_reply()
    {
		factory('App\ThreadReply')->create(['thread_id'=>$this->thread->id]);
		$this->assertInstanceOf('App\ThreadReply',$this->thread->replies[0]);
    }

	/** @test */
	public function a_reply_can_be_added() {
		$reply = array(
			'body'=>'foobar',
			'user_id'=>1
		);
		$this->thread->addReply($reply);
		$this->assertCount(1,$this->thread->replies);
	}

	/** @test */
	public function a_thread_belongs_to_a_channel() {
		$this->assertInstanceOf('App\Category',$this->thread->category);
	}

	/** @test */
	public function a_thread_has_a_path_with_category_slug() {
		$this->assertEquals($this->thread->getPath(),'/forum/'.$this->thread->category->slug.'/'.$this->thread->slug);
	}

	/** @test */
	public function a_thread_has_subscribed_users() {
		$thread = factory('App\Thread')->create();
		$this->signIn();
		$thread->subscribe();
		$this->assertCount(1,$thread->subscriptions()->where('user_id',auth()->id())->get());
	}

	/** @test */
	public function a_thread_can_be_subscribed_and_unsubscribed_to() {
		$this->signIn();
		$this->thread->subscribe();
		$this->assertCount(1,$this->thread->subscriptions()->where(['user_id'=>auth()->id()])->get());
		$this->thread->unsubscribe();
		$this->assertCount(0,$this->thread->subscriptions);
	}

	/** @test */
	public function it_knows_if_a_user_is_subscribed() {
		$this->signIn();
		$this->assertTrue(!$this->thread->is_subscribed);
		$this->thread->subscribe();
		$this->assertTrue($this->thread->refresh()->is_subscribed);
	}

	/** @test */
	public function a_thread_checks_if_it_has_been_updated_since_the_users_last_visit() {
		$this->signIn();
		$this->assertTrue($this->thread->hasBeenUpdated());
		auth()->user()->read($this->thread->id);
		$this->assertFalse($this->thread->hasBeenUpdated());
	}

	/** @test */
	// public function a_thread_has_a_best_reply() {
	// 	$thread = factory('App\Thread')->create();
	// 	$reply = factory('App\ThreadReply')->create();
	// 	$best_reply = factory('App\ThreadReply')->create();
	// 	$best_reply->markAsBest();
	// 	$this->assertEquals($best_reply->id,$thread->refresh()->bestReply()->id);
	// }


}
