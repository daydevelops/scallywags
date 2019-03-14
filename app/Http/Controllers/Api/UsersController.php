<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function index() {
		$search = request('name');
		return \App\User::where('name','LIKE',"$search%")->take(5)->pluck('name');
	}
}
