<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use \App\Inspections\Spam;

class SpamTest extends TestCase
{
	use DatabaseMigrations;

	public function setup() {
		parent::setup();
		$this->spam = new Spam;

	}

    /** @test */
    public function it_can_detect_invalid_keywords()
    {
		$this->assertFalse($this->spam->detect('Innocent text here'));
		$this->expectException('Exception');
		$this->spam->detect('Yahoo Customer Support');
    }

    /** @test */
    public function it_can_detect_keys_held_down() {
		$this->expectException('Exception');
		$this->spam->detect('hello world aaaaaaaaaaaaaaaaaaaaaa');
	}

}
