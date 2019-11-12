<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Conversation;

class ConversationTest extends TestCase
{

    use DatabaseMigrations;
    
	public function setup() {
		parent::setup();

		$this->user = factory('App\User')->create();
		$this->convo = factory('App\Conversation')->create();
		DB::table('conversation_user')->insert([
			'conversation_id'=>$this->convo->id,
			'user_id'=>$this->user->id
		]);
    }
    

    /** @test */
    public function it_has_users() {
        $this->assertInstanceOf('App\User',$this->convo->users[0]);
    }

    /** @test */
    public function it_has_messages() {
		factory('App\Message')->create([
			'conversation_id'=>$this->convo->id
		]);
		$this->assertInstanceOf('App\Message',$this->convo->messages[0]);
    }
}
