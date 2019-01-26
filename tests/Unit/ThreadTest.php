<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

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
}
