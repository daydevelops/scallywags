<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

class UserTest extends TestCase
{
	use DatabaseMigrations;

	public function setup() {
		parent::setup();

		$this->user = factory('App\User')->create();
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

	/** @test */
	public function it_can_get_its_last_thread_reply() {
		factory('App\ThreadReply')->create([
			'user_id'=>$this->user->id,
			'created_at'=>\Carbon\Carbon::yesterday()
		]);
		$reply = factory('App\ThreadReply')->create(['user_id'=>$this->user->id]);
		$this->assertEquals($reply->id,$this->user->lastReply->id);
	}

	/** @test */
	public function it_has_favourites() {
		$this->SignIn();
		// given that we have favourited some threads and replies
		$t1 = factory('App\Thread')->create();
		$t2 = factory('App\Thread')->create();
		$r1 = factory('App\ThreadReply')->create(['thread_id'=>$t1->id]);
		$r2 = factory('App\ThreadReply')->create(['thread_id'=>$t1->id]);
		factory('App\Favourite')->create([
			'user_id'=>auth()->id(),
			'favourited_id'=>$t1->id,
			'favourited_type'=>'App\Thread'
		]);
		factory('App\Favourite')->create([
			'user_id'=>auth()->id(),
			'favourited_id'=>$r1->id,
			'favourited_type'=>'App\ThreadReply'
		]);
		// the user should own those items
		$favs = auth()->user()->favourites();
		// dd($favs);
		$this->assertEquals($t1->title,$favs[0]->favourited()->get()[0]->title);
		$this->assertEquals($r1->title,$favs[1]->favourited()->get()[0]->title);
	}

	/** @test */
	public function it_has_chats() {
		$chat = factory('App\Chat')->create();
		DB::table('chat_user')->insert([
			'chat_id'=>$chat->id,
			'user_id'=>$this->user->id
		]);
		$this->assertInstanceOf('App\Chat',$this->user->chats[0]);
	}

	/** @test */
	public function it_has_messages() {
		factory('App\Message')->create([
			'user_id'=>$this->user->id
		]);
		$this->assertInstanceOf('App\Message',$this->user->messages[0]);
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
