<?php

namespace App\Http\Controllers;

use App\Chat;
use Illuminate\Http\Request;

class ChatsController extends Controller
{
	public function __construct() {
		$this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chats = auth()->user()->chats()->orderBy('chat_user.last_contribution','DESC')->get();

        if (request()->wantsJson()) {
			return $chats;
		}
        return view('chats/all',compact('chats'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = request()->validate([
            'user_ids'=>'required|array',
            'user_ids.*'=>'int',
            'message'=>'required'
        ]);
        //add auth user to the chat
        array_push($data['user_ids'],auth()->id());
        $data['user_ids'] = array_unique($data['user_ids']);

        if (Chat::alreadyExists($data['user_ids'])) {
            return response()->json("You already have a chat room for these users",406); // 406 => not acceptable
        }

        $chat = Chat::startNew($data);
        response()->json(['chat' => $chat],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function show(Chat $chat)
    {
        return $chat;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function edit(Chat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chat $chat)
    {
        //
    }

    public function hasRead(Chat $chat) {
        $chat->viewed();
        
        // clear the notification for this chat
        $notifications = auth()->user()->unreadNotifications()->where([
			'type'=>'App\Notifications\NewMessage'
        ])->get();
        forEach($notifications as $note) {
            if ($note->data['chat_id'] == $chat->id) {
                $note->markAsRead();
                break;
            }
        };

        
    }
}
