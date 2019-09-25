<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class GameTest extends TestCase
{
	use DatabaseMigrations;

	public function setup() {
		parent::setup();

		// $this->game = factory('App\Game')->create();
	}

	/** @test */
	public function it_has_players() {
		$game = factory('App\Game')->create();
		$user = factory('App\User')->create();
		\App\GameUser::create([
			'game_id'=>$game->id,
			'user_id'=>$user->id,
			'confirmed'=>1
		]);
		$this->assertInstanceOf('App\User',$game->users[0]);
		$this->assertCount(0,$game->invites);
	}

	/** @test */
	public function it_has_invites() {
		$game = factory('App\Game')->create();
		$user = factory('App\User')->create();
		\App\GameUser::create([
			'game_id'=>$game->id,
			'user_id'=>$user->id,
			'confirmed'=>0
		]);
		$this->assertInstanceOf('App\User',$game->invites[0]);
		$this->assertCount(0,$game->users);
	}

	/** @test */
	public function it_has_an_event() {
		$game = factory('App\Game')->create();
		\App\Event::bookCourt($game->gamedate);
		$this->assertInstanceOf('App\Event',$game->event);
	}


}
