<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class DashboardController extends Controller
{
    function __construct() {
        $this->middleware('auth');
    }
    function index() {
        // show dashboard view with users information 
        $user = auth()->user()->load('threads','replies');
        return view('users/dashboard',compact('user'));
    }
}
