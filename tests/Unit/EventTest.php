<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use \App\Event;

class EventTest extends TestCase
{
	use DatabaseMigrations;

	public function setup() {
		parent::setup();

		// $this->game = factory('App\Game')->create();
	}

	/** @test */
	public function it_has_games() {
		$game = factory('App\Game')->create();
		\App\Event::bookCourt($game->gamedate);
		$this->assertInstanceOf('App\Game',Event::first()->games[0]);
	}

	/** @test */
	public function it_can_be_booked () {
		$date = date('c',time());
		Event::bookCourt($date);
		$this->assertCount(1,Event::where(['start'=>$date])->get());
	}

	/** @test  */
	public function an_event_can_have_only_2_games() {
		$date = date('c',time());
		factory('App\Game',2)->create(['gamedate'=>$date]);
		Event::bookCourt($date);
		Event::bookCourt($date);
		$this->assertEquals(0,Event::bookCourt($date));
	}


}
