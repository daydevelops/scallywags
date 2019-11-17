<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ProfileController extends Controller
{

	public function __construct() {
		$this->middleware('auth');
	}
    public function show(User $user) {
		$activities = $user->activities()->with('subject')->latest()->get();
		$chat_exists = \App\Chat::alreadyExists([$user->id,auth()->id()]);
		return view('users/profile',compact('user','activities','chat_exists'));
	}
}
