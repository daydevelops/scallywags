<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class Thread extends TestCase
{
	use DatabaseMigrations;
    /** @test */
    public function it_has_a_user()
    {
		$thread = factory('App\Thread')->create();
		$this->assertInstanceOf('App\User',$thread->user);
    }
	/** @test */
	public function it_owns_a_reply()
    {
		$thread = factory('App\Thread')->create();
		factory('App\ThreadReply')->create(['thread_id'=>$thread->id]);
		$this->assertInstanceOf('App\ThreadReply',$thread->replies[0]);
    }
}
