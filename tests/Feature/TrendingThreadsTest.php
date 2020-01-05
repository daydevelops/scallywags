<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Redis;

class TrendingThreadsTest extends TestCase
{
	use DatabaseMigrations;

	public function setup(): void {
		parent::setup();
		Redis::del('trending_threads_test');
	}

	/** @test */
	public function it_increments_a_thread_score_when_visited()
	{
		$this->assertEmpty(Redis::zrevrange('trending_threads_test',0,-1));
		$thread = factory('App\Thread')->create();
		$this->call('GET',$thread->getPath());
		$trending = Redis::zrevrange('trending_threads_test',0,-1);
		$this->assertEquals($thread->title,json_decode($trending[0])->title);
	}


}
