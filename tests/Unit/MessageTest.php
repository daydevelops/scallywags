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
}
