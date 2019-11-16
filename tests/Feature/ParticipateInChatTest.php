<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use App\Message;

class ParticipateInChatTest extends TestCase
{
    use DatabaseMigrations;

    public function createChat() {
        // set up a chat between 2 users with some messages

        // create 2 users, one is signed in
        $this->signIn();
        $this->user2 = factory('App\User')->create();

        // create the chat and add the users via the pivot table
		$this->chat = factory('App\Chat')->create();
		DB::table('chat_user')->insert([
			'chat_id'=>$this->chat->id,
			'user_id'=>auth()->id()
		]);
		DB::table('chat_user')->insert([
			'chat_id'=>$this->chat->id,
			'user_id'=>$this->user2->id
        ]);

        // add 1 message per user
        $this->msg1 = factory('App\Message')->create([
            'user_id'=>auth()->id(),
            'chat_id'=>$this->chat->id
        ]);
        $this->msg2 = factory('App\Message')->create([
            'user_id'=>$this->user2->id,
            'chat_id'=>$this->chat->id
        ]);
    }
    
    /** @test */
    public function a_user_can_view_their_chats() {
        $this->createChat();
        $response = $this->get('/chats');
        $response->assertSee($this->user2->name);
    }
    
    /** @test */
    public function a_user_can_not_view_another_users_chats() {
        $this->createChat();
        $this->signIn();
        $response = $this->get('/chats');
        $response->assertDontSee($this->user2->name);
    }

    /** @test */
    public function a_user_can_create_a_new_message() {
        $this->createChat();
        $this->post('/chats/'.$this->chat->id.'/messages',['body'=>'testing']);
        $this->assertDatabaseHas('messages',[
            'body'=>'testing',
            'chat_id'=>$this->chat->id,
            'user_id'=>auth()->id()
        ]);
    }
}
