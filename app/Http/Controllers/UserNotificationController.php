<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserNotificationController extends Controller
{
	public function __construct() {
		$this->middleware('auth');
	}

	public function index() {
		return (auth()->user()->unreadNotifications);
	}
    public function destroyAll() {
		auth()->user()->unreadNotifications->markAsRead();
	}
	public function destroy($note_id) {
		auth()->user()->unreadNotifications()->findOrFail($note_id)->markAsRead();
	}
}
