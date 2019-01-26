<?php

namespace App\Http\Controllers;

use App\ThreadReply;
use App\Thread;
use Illuminate\Http\Request;

class ThreadReplyController extends Controller
{
	public function __construct() {
		$this->middleware('auth')->except(['index','show']);
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request, $category_id, Thread $thread)
    {
        $thread->addReply([
			'body'=>$request->body,
			'user_id'=>auth()->user()->id
		]);
		return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ThreadReply  $threadReply
     * @return \Illuminate\Http\Response
     */
    public function show(ThreadReply $threadReply)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ThreadReply  $threadReply
     * @return \Illuminate\Http\Response
     */
    public function edit(ThreadReply $threadReply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ThreadReply  $threadReply
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ThreadReply $threadReply)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ThreadReply  $threadReply
     * @return \Illuminate\Http\Response
     */
    public function destroy(ThreadReply $threadReply)
    {
        //
    }
}
