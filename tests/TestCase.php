<?php

namespace Tests;

use App\Exceptions\Handler;
Use App\Rules\Recaptcha;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Auth;

abstract class TestCase extends BaseTestCase
{
	use CreatesApplication;

	protected function setUp() {
		parent::setUp();
		$this->disableExceptionHandling();


		app()->singleton(Recaptcha::class,function() {
			$m = \Mockery::mock(Recaptcha::class);
			$m->shouldReceive('passes')->andReturn(true);
			return $m;
		});
	}
	protected function disableExceptionHandling() {
		$this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);
		$this->app->instance(ExceptionHandler::class, new class extends Handler {
			public function __construct() {

			}
			public function report(\Exception $e) {

			}
			public function render($request, \Exception $e) {
				throw $e;
			}
		});
	}
	protected function withExceptionHandling() {
		$this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);
		return $this;
	}

	protected function signIn($user=null) {
		$user = $user ?: factory('App\User')->create();
		$this->be($user);
		return $this;
	}

	protected function signout() {
		Auth::logout();
	}

}
