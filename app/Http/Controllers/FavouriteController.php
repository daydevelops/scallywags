<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;
use App\ThreadReply;
use App\Favourite;

class FavouriteController extends Controller
{
	public function __construct() {
		$this->middleware('auth');
	}
    public function storeThread(Thread $thread) {
		$thread->favourite();
	}
	public function storeReply(ThreadReply $reply) {
		$reply->favourite();
	}
}
