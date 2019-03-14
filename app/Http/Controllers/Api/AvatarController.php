<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AvatarController extends Controller
{
	public function __construct() {
		$this->middleware('auth');
	}

    public function store() {
		// dd(request()->file('avatar'));
		request()->validate([
			'avatar' => ['required','image']
		]);
		auth()->user()->update([
			'image'=>request()->file('avatar')->store('avatars','public')
		]);
		return auth()->user()->image;
	}
}
