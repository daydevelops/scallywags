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

	/** @test */
	public function it_wraps_user_mentions_with_an_anchor_tag() {
		$this->signIn();
		$user = factory('App\User')->create(['name'=>'alice-day']);
		// $user = factory('App\User')->create(['name'=>'alice']);
		$thread = factory('App\Thread')->create();
		$reply = factory('App\ThreadReply')->make(['body'=>"hello @bob and @alice-day."]);
		$this->post($thread->getPath().'/reply',$reply->toArray());

		$this->assertContains('<a href="'.$user->getPath().'">@alice-day</a>',$reply->refresh()->body);
	}
}
