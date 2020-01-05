<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CategoryTest extends TestCase
{
	use DatabaseMigrations;

	public function setup(): void {
		parent::setup();

		$this->category = factory('App\Category')->create();
	}

	/** @test */
	public function a_category_has_threads() {
		$thread = factory('App\Thread')->create(['category_id'=>$this->category->id]);
		$this->assertInstanceOf('App\Thread',$this->category->threads()->first());
	}
}
