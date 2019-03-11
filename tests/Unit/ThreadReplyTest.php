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
	/** @test */
	public function it_knows_if_it_was_just_published()
	{
		$reply = factory('App\ThreadReply')->create();
		$this->assertTrue($reply->wasJustPublished());
		$reply = factory('App\ThreadReply')->create(['created_at'=>\Carbon\Carbon::now()->subSeconds(31)]);
		$this->assertFalse($reply->wasJustPublished());
	}
}
