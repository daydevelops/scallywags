<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadReplyTest extends TestCase
{
	use DatabaseMigrations;
    /** @test */
    public function it_has_a_user()
    {
		$reply = factory('App\ThreadReply')->create();
		$this->assertInstanceOf('App\User',$reply->user);
    }
	/** @test */
	public function it_belongs_to_a_thread()
    {
		$reply = factory('App\ThreadReply')->create();
		$this->assertInstanceOf('App\Thread',$reply->thread);
    }
}
