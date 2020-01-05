<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Test;

class ChatTest extends TestCase
{

    use DatabaseMigrations;
    
	public function setup(): void {
		parent::setup();

		$this->user = factory('App\User')->create();
		$this->chat = factory('App\Chat')->create();
		DB::table('chat_user')->insert([
			'chat_id'=>$this->chat->id,
			'user_id'=>$this->user->id
		]);
    }
    

    /** @test */
    public function it_has_users() {
        $this->assertInstanceOf('App\User',$this->chat->users[0]);
    }

    /** @test */
    public function it_has_messages() {
		factory('App\Message')->create([
			'chat_id'=>$this->chat->id
		]);
		$this->assertInstanceOf('App\Message',$this->chat->messages[0]);
	}
	
	/** @test */
	public function it_knows_if_someone_is_a_member() {
		$user2 = factory('App\User')->create();
		$this->assertTrue($this->chat->isUser($this->user->id));
		$this->assertTrue( ! $this->chat->isUser($user2->id));
	} 
}
