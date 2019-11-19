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

    /** @test */
    public function a_new_msg_is_automatically_read_by_its_owner() {
        $this->createChat();
        $this->post('/chats/'.$this->chat->id.'/messages',['body'=>'testing']);
        $this->assertTrue(
            Message::where(['body'=>'testing'])->first()->hasViewed(auth()->id())
        );
    }

    /** @test */
    public function a_user_cannot_send_a_message_to_a_chat_they_dont_own() {
        $this->createChat();
        $this->signIn();
		$this->withExceptionHandling()->json('post','/chats/'.$this->chat->id.'/messages',['body'=>'testing'])->assertStatus(403);
    }

    /** @test */
    public function a_user_is_notified_when_sent_a_message() {
        $this->createChat();
        $this->post('/chats/'.$this->chat->id.'/messages',['body'=>'testing']);
        $this->assertDatabaseHas('notifications',[
			'notifiable_type'=>'App\User',
			'type'=>'App\Notifications\NewMessage',
			'notifiable_id'=>$this->user2->id
		]);
    }

    /** @test */
    public function a_user_is_not_notified_of_their_own_message() {
        $this->createChat();
        $this->post('/chats/'.$this->chat->id.'/messages',['body'=>'testing']);
        $this->assertDatabaseMissing('notifications',[
			'notifiable_type'=>'App\User',
			'type'=>'App\Notifications\NewMessage',
			'notifiable_id'=>auth()->id()
        ]);
    }

    /** @test */
    public function a_user_can_read_all_chat_messages() {
        $this->createChat();
        $this->post('/chats/'.$this->chat->id.'/messages',['body'=>'testing']);
        $msg = Message::where(['body'=>'testing'])->first();
        $this->signIn($this->user2);
        $this->assertFalse($msg->hasViewed(auth()->id()));
        $this->post('/chats/'.$this->chat->id.'/read');
        $this->assertTrue($msg->hasViewed(auth()->id()));
    }

    /** @test */
    public function the_user_sees_only_one_notif_per_user_when_sent_multiple_msgs() {
        $this->assertCount(0,DB::table('notifications')->get());
        $this->createChat();
        $this->post('/chats/'.$this->chat->id.'/messages',['body'=>'testing']);
        $this->post('/chats/'.$this->chat->id.'/messages',['body'=>'testing2']);
        $this->signIn($this->user2);
        $this->assertCount(1,$this->user2->unreadNotifications);

    }

    /** @test */
    public function when_a_msg_is_read_the_notification_is_cleared() {

        // create chat, sned a message and make sure there is a notification
        $this->createChat();
        $user1 = auth()->user();
        $this->post('/chats/'.$this->chat->id.'/messages',['body'=>'testing']);
        $msg = Message::where(['body'=>'testing'])->first();

        $this->signIn($this->user2);

        $this->assertFalse($msg->hasViewed);
        $this->assertDatabaseHas('notifications',[
			'notifiable_type'=>'App\User',
			'type'=>'App\Notifications\NewMessage',
            'notifiable_id'=>$this->user2->id,
            'read_at'=>null
        ]);

        // post another message so we can test that the other user gets a notification,
        // which does not get deleted.
        $this->post('/chats/'.$this->chat->id.'/messages',['body'=>'testing2']);

        // read all messages in this chat
        $this->post('/chats/'.$this->chat->id.'/read');

        // test that the auth users notification is deleted
        $this->assertTrue($msg->fresh()->hasViewed);
        $this->assertDatabaseMissing('notifications',[
			'notifiable_type'=>'App\User',
			'type'=>'App\Notifications\NewMessage',
			'notifiable_id'=>$this->user2->id,
            'read_at'=>null
        ]);

        // and the other user still has a notification
        $this->assertDatabaseHas('notifications',[
			'notifiable_type'=>'App\User',
			'type'=>'App\Notifications\NewMessage',
			'notifiable_id'=>$user1->id,
            'read_at'=>null
        ]);
    }

    /** @test */
    public function a_user_can_start_a_chat() {
        $this->signIn();
        $user = factory('App\User')->create();
        $this->assertDatabaseMissing('chats',['id'=>1]);
        $response = $this->post('/chats',[
            'user_ids'=>[$user->id],
            'message'=>'TESTING'
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('chats',['id'=>1]);
        $this->assertDatabaseHas('chat_user',[
            'chat_id'=>1,
            'user_id'=>auth()->id()
        ]);
        $this->assertDatabaseHas('chat_user',[
            'chat_id'=>1,
            'user_id'=>$user->id
        ]);
        $this->assertDatabaseHas('messages',[
            'body'=>'TESTING',
            'chat_id'=>1,
            'user_id'=>auth()->id()
        ]);
        $this->assertDatabaseHas('notifications',[
			'notifiable_type'=>'App\User',
			'type'=>'App\Notifications\NewMessage',
			'notifiable_id'=>$user->id
        ]);

    }

    /** @test */
    public function chat_rooms_cannot_be_duplicated() {
        // cannot create a duplicate chat room between the same combination of users
        $this->createChat();
        $this->createChat();
        $response = $this->post('/chats',[
            'user_ids'=>[$this->user2->id],
            'message'=>'TESTING'
        ])->assertStatus(406);
        $this->assertDatabaseHas('chats',['id'=>2]);
        $this->assertDatabaseMissing('chats',['id'=>3]);
    }

    /** @test */
    public function users_cannot_be_added_to_a_chat_more_than_once() {
        $this->signIn();
        $user = factory('App\User')->create();
        $response = $this->post('/chats',[
            'user_ids'=>[$user->id,$user->id],
            'message'=>'TESTING'
        ]);
        $this->assertCount(1,DB::table('chat_user')->where(['user_id'=>$user->id])->get());
    }
}
