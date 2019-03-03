<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
	use DatabaseMigrations;

	public function setup() {
		parent::setup();

		$this->user = factory('App\User')->create();
	}

	/** @test */
	public function it_can_be_a_game_player()
	{
		$game = factory('App\Game')->create();
		\App\GameUser::create([
			'game_id'=>$game->id,
			'user_id'=>$this->user->id,
			'confirmed'=>1
		]);
		$this->assertInstanceOf('App\Game',$this->user->games[0]);
	}

	/** @test */
	public function it_can_have_a_game_invite()
	{
		$game = factory('App\Game')->create();
		\App\GameUser::create([
			'game_id'=>$game->id,
			'user_id'=>$this->user->id,
			'confirmed'=>0
		]);
		$this->assertInstanceOf('App\Game',$this->user->invites[0]);
	}

	/** @test */
	public function it_owns_a_thread() {
		factory('App\Thread')->create(['user_id'=>$this->user->id]);
		$this->assertInstanceOf('App\Thread',$this->user->threads[0]);
	}

	/** @test */
	public function it_owns_a_reply() {
		factory('App\ThreadReply')->create(['user_id'=>$this->user->id]);
		$this->assertInstanceOf('App\ThreadReply',$this->user->replies[0]);
	}

	/** @test */
	public function it_can_have_activities() {
		factory('App\Thread')->create(['user_id'=>$this->user->id]);
		$this->assertInstanceOf('App\ForumActivity',$this->user->activities[0]);
	}

	// /** @test */
	// public function it_has_thread_subscriptions() {
	// 	$thread = factory('App\Thread')->create(['user_id'=>999]);
	// 	$this->signIn();
	// 	$thread->subscribe();
	// 	$this->user->subscriptions;
	// 	dd($this->user);//->subscriptions->where(['thread_id'=>$thread->id])->get());
	// 	// dd(\App\ThreadSubscription::where(['user_id'=>auth()->id()])->get());
	//
	// 	$this->assertInstanceOf('App\ThreadSubscription',$this->user->subscriptions[0]);
	// }

}
