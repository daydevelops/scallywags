<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Game;

class DashboardController extends Controller
{
    function __construct() {
        $this->middleware('auth');
    }
    function index() {
        // show dashboard view with users information, scheduled games, and payment status
        $user = auth()->user();
        $games = $user->games;
        $users = User::all(); // give list of users so the user can invite others to games
        // dd($games);
        return view('users/dashboard',compact('user','games','users'));
    }

    function editGame(Game $game) {
        if ($game->isPlaying()) {
            return response()->json(array('status'=>1,'game'=>$game,'players'=>$game->users,'invites'=>$game->invites));
        } else {
            return response()->json(array('status'=>0,'fb'=>'You do not have permission to edit this game'));
        }
    }
}
