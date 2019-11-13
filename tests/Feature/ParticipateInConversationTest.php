<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use App\Message;

class ParticipateInConversationTest extends TestCase
{
    use DatabaseMigrations;

    public function createConvo() {
        // set up a convo between 2 users with some messages

        // create 2 users, one is signed in
        $this->signIn();
        $this->user2 = factory('App\User')->create();

        // create the convo and add the users via the pivot table
		$this->convo = factory('App\Conversation')->create();
		DB::table('conversation_user')->insert([
			'conversation_id'=>$this->convo->id,
			'user_id'=>auth()->id()
		]);
		DB::table('conversation_user')->insert([
			'conversation_id'=>$this->convo->id,
			'user_id'=>$this->user2->id
        ]);

        // add 1 message per user
        $this->msg1 = factory('App\Message')->create([
            'user_id'=>auth()->id(),
            'conversation_id'=>$this->convo->id
        ]);
        $this->msg2 = factory('App\Message')->create([
            'user_id'=>$this->user2->id,
            'conversation_id'=>$this->convo->id
        ]);
    }
    
    /** @test */
    public function a_user_can_view_their_convos() {
        $this->createConvo();
        $response = $this->get('/conversations');
        $response->assertSee($this->user2->name);
    }
}
