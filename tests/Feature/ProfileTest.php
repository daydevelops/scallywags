<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfileTest extends TestCase
{
	use DatabaseMigrations;

	public function setup(): void {
		parent::setup();
	}

	/** @test */
	public function an_auth_user_can_view_a_users_profile()
	{
		$this->signIn();
		$user = factory('App\User')->create();
		$response = $this->get('/profile/'.$user->id);
		$response->assertSee($user->name);
	}

	/** @test */
	public function an_unauth_user_cannot_view_a_users_profile()
	{
		$this->expectException('Illuminate\Auth\AuthenticationException');
		$user = factory('App\User')->create();
		$response = $this->get('/profile/'.$user->id);
	}

	/** @test */
	public function a_profile_contains_the_users_created_threads() {
		$this->signIn();

		$threads = factory('App\Thread',2)->create();

		$this->get('profile/'.$threads[0]->user_id)
		->assertSee($threads[0]->title)
		->assertDontSee($threads[1]->body);
	}

	/** @test */
	public function a_profile_contains_the_users_created_replies() {
		$this->signIn();

		$reply = factory('App\ThreadReply')->create();

		$this->get('profile/'.$reply->user_id)
		->assertSee($reply->body);
	}

	/** @test */
	public function a_profile_contains_the_users_favourited_items() {
		$this->signIn();
		$thread = factory('App\Thread')->create();
		$thread->favourite();
		$this->get('profile/'.auth()->id())
		->assertSee("favourited a thread")
		->assertSee($thread->title);
	}

	/** @test */
	public function a_guest_cannot_upload_avatars() {
		$this->expectException('Illuminate\Auth\AuthenticationException');
		$this->post('/profile/avatar',[]);
	}

	/** @test */
	public function an_invalid_avatar_is_not_uploaded() {
		$this->signIn();
		$this->withExceptionHandling()->json('post','/profile/avatar',[
			'avatar' => 'not-an-image'
		])->assertStatus(422);
	}

	/** @test */
	public function a_user_can_upload_an_avatar() {
		$this->signIn();
		$avatar = UploadedFile::fake()->image('avatar.jpg');
		Storage::fake('public');
		$this->withExceptionHandling()->json('post','/profile/avatar',[
			'avatar' => $avatar
		])->assertStatus(200);
		Storage::disk('public')->assertExists('avatars/'.$avatar->hashName());
		$this->assertEquals('avatars/'.$avatar->hashName(),auth()->user()->image);
	}

}
