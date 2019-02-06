<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ForumActivityTest extends TestCase
{
	use DatabaseMigrations;

	public function setup() {
		parent::setup();
	}

    /** @test */
    public function it_logs_a_thread_creation()
    {
		$this->signIn();
		$thread = factory('App\Thread')->create(['user_id'=>auth()->id()]);
		// dd($thread->id);
		$this->assertDatabaseHas('forum_activities',array(
			'user_id'=>auth()->id(),
			'subject_id'=>$thread->id,
			'subject_type'=>'App\Thread'
		));

    }

	/** @test */
	public function it_logs_a_reply_creation()
	{
	 $this->signIn();
	 $reply = factory('App\ThreadReply')->create(['user_id'=>auth()->id()]);
	 // dd($thread->id);
	 $this->assertDatabaseHas('forum_activities',array(
		 'user_id'=>auth()->id(),
		 'subject_id'=>$reply->id,
		 'subject_type'=>'App\ThreadReply'
	 ));

	}
}
