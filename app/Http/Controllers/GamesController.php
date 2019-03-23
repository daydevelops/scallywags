<?php

namespace App\Http\Controllers;

use App\Game;
use App\User;
use App\Event;
use App\GameUser;
use Illuminate\Http\Request;

class GamesController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except(['index','schedule']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        // the user is trying to access the schedule
        // show all games
        $users = User::all(); // give list of users so the user can invite others to games
        $games = Game::all();
        return view('games/schedule',compact('games','users'));
    }

    public function schedule() {
        // load games into the calendar
        $games = Game::select('id','gamedate','private','event_id')->orderBy('gamedate','ASC')->get();
        foreach ($games as $g) {
            if (!$g->private || $g->isPlaying()) {
                $g->usersPublic;
                $g->invitesPublic;
            }
            $g->isPlaying = $g->isPlaying();
        }
        $events = Event::all();
		$users = User::allPublic();
        return compact('games','events','users');
    }

    public function show(Game $game) {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $data = request()->validate([
            'private'=>'required',
            'gamedate'=>'required'
        ]);
        $data['admin-created'] = auth()->user()->is_admin;
        // return null;
        // try to book the court
        $event_id = Event::bookCourt($data['gamedate']); // 0 -> all booked out, int>0 -> event_id
        if ($event_id==0) { // both courts booked
            return response()->json(array('status'=>0,'fb'=>'both courts are booked'));
        } else {
            $data['event_id']=$event_id;
        }

        $game = Game::create($data);
		GameUser::create([
			'game_id'=>$game->id,
			'user_id'=>auth()->id(),
			'confirmed'=>1
		]);
        if ($request->input('users')!=null) { // jquery ajax wont send users if array is empty
            foreach ($request->input('users') as $u) {
                $game->invite((int)$u);
            }
        }
        return response()->json(array('status'=>1,'fb'=>'Court has been booked'));
    }

    public function togglePrivate(Request $request,Game $game) {
        // dd($request);
        if ($game->private) {
            $response = $game->makePublic();
        } else {
            $response = $game->makePrivate();
        }
        return response()->json(array('status'=>1,'private'=>Game::find($game->id)->private));
    }

    public function invite(Request $request,Game $game,User $user) {
        return response()->json($game->invite($user->id));

    }

    public function leave(Game $game) {
        return response()->json($game->leave());
    }
    public function join(Game $game) {
        return response()->json($game->join());
    }

	public function test() {
		$data = array(
            'private'=>'1',
            'gamedate'=>time
        );
        $data['admin-created'] = auth()->user()->is_admin;
        // echo json_encode($data);
        // return null;
        // try to book the court
        $event_id = Event::bookCourt($data['gamedate']); // 0 -> all booked out, int>0 -> event_id
        if ($event_id==0) { // both courts booked
            return response()->json(array('status'=>0,'fb'=>'both courts are booked'));
        } else {
            $data['event_id']=$event_id;
        }

        $game = Game::create($data);
        $game->join();
        if ($request->input('users')!=null) { // jquery ajax wont send users if array is empty
            foreach ($request->input('users') as $u) {
                $game->invite((int)$u);
            }
        }
        return response()->json(array('status'=>1,'fb'=>'Court has been booked'));
	}
}
