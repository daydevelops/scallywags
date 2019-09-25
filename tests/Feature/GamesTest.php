<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Game;
use App\Event;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class GamesTest extends TestCase
{
	use DatabaseMigrations;

	public function setup() {
		parent::setup();
	}

	/** @test  */
	public function a_user_can_see_public_game_info() {
		$game = factory('App\Game')->create(['private'=>0]);
		$this->signIn();
		$game->join();
		$data = $this->json('get','/game/schedule')->original;
		$this->assertContains(auth()->user()->name,json_encode($data['games']));
	}

	/** @test  */
	public function a_user_cannot_see_private_game_info() {
		$game = factory('App\Game')->create(['private'=>1]);
		$this->signIn();
		$game->join();
		$data = $this->json('get','/game/schedule')->original;
		$this->assertNotContains(auth()->user()->name,json_encode($data['games']));
	}

	/** @test  */
	public function a_guest_cannot_book_a_game() {
		$this->expectException('Illuminate\Auth\AuthenticationException');
		$this->post('/game');
	}

	/** @test  */
	public function a_guest_cannot_join_a_game() {
		$this->expectException('Illuminate\Auth\AuthenticationException');
		$this->post('/game/1/join');
	}

	/** @test  */
	public function a_user_can_book_a_game() {
		$this->signIn();
		// dd(auth()->user()->is_admin);
		$this->post('/game',[
			'gamedate'=>date('c'),
			'private'=>0
		]);
		// dd(Game::all());

	}

	/** @test  */
	// public function a_user_can_invite_other_players_to_a_new_game() {
	//
	// }

	/** @test  */
	// public function a_user_can_join_a_public_game() {
	//
	// }

	/** @test  */
	// public function a_user_can_leave_a_game() {
	//
	// }

	/** @test  */
	// public function a_user_can_invite_others_to_a_game() {
	//
	// }

	/** @test  */
	// public function an_event_is_created_for_a_game() {
	// 	$this->post('/game',[
	// 		'gamedate'=>time(),
	// 		'isPrivate'=>0
	// 	])
	// }

	/** @test  */
	// public function a_user_cannot_book_more_than_2_games_per_slot() {
	//
	// }

}
