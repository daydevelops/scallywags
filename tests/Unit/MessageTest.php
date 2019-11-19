<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Message;

class MessageTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function it_has_users() {
		factory('App\Message')->create();
        $this->assertInstanceOf('App\User',Message::first()->user);
    }

    /** @test */
    public function it_has_a_chat() {
		factory('App\Message')->create();
        $this->assertInstanceOf('App\Chat',Message::first()->chat);
    }

    /** @test */
    public function it_has_views() {
        $msg = factory('App\Message')->create();
		DB::table('message_views')->insert([
			'message_id'=>$msg->id,
			'user_id'=>1
        ]);
        $this->assertEquals(1,$msg->views()[0]->user_id);
    }

    /** @test */
    public function it_knows_if_it_has_been_viewed() {
        $msg = factory('App\Message')->create();
        $this->signIn();
        $this->assertFalse($msg->hasViewed(auth()->id()));
		DB::table('message_views')->insert([
			'message_id'=>$msg->id,
			'user_id'=>auth()->id()
        ]);
        $this->assertTrue($msg->hasViewed(auth()->id()));
    }

    /** @test */
    public function a_message_can_be_viewed() {
        $msg = factory('App\Message')->create();
        $this->signIn();
        $this->assertFalse($msg->hasViewed(auth()->id()));
		$msg->viewed();
        $this->assertTrue($msg->hasViewed(auth()->id()));
    }

    /** @test */
    public function only_one_view_record_per_user_can_be_created() {
        $msg = factory('App\Message')->create();
        $this->signIn();
		$msg->viewed();
        $this->assertCount(1,$msg->views());
        $msg->viewed();
        $this->assertCount(1,$msg->views());
    }
}
