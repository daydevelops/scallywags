<?php

namespace App\Http\Controllers;

use App\Message;
use App\Chat;
use Illuminate\Http\Request;

class MessagesController extends Controller
{

    public function index(Chat $chat, Request $request) {
        return $chat->messages(request('has'),request('wants'))->get()->reverse()->values()->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Chat $chat, Request $request)
    {
        if ( ! $chat->isUser(auth()->id())) {
            return response()->json(['error' => 'Not authorized.'],403);
        }

        $data = request()->validate([
            'body'=>'required',
        ]);
        $data['user_id'] = auth()->user()->id;
        $data['chat_id'] = $chat->id;
        $chat->addMessage($data);
    }

}
