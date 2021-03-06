<?php

namespace App\Http\Controllers;

use App\ThreadReply;
use App\Thread;
use App\Category;
use App\User;
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
	}

	/**
	* Store a newly created resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @return \Illuminate\Http\Response
	*/
	public function store(Request $request, $category_id, Thread $thread)
	{
		try {
			if ($thread->is_locked) {
				return response('Thread is locked.',422);
			}
			$this->authorize('create',new ThreadReply);
			$data = request()->validate([
				'body'=>'required|spamfree'
			]);
			$data['user_id'] = auth()->user()->id;

			$reply = $thread->addReply($data);


			if (request()->expectsJson()) {
				return $reply->load('user');
			}
			return back();

		} catch (\Exception $e) {
			return response('Sorry, we could not process your request',422);
		}
	}

	/**
	* Display the specified resource.
	*
	* @param  \App\ThreadReply  $threadReply
	* @return \Illuminate\Http\Response
	*/
	public function show($category, Thread $thread)
	{
		return $thread->replies()->paginate(10);
	}

	/**
	* Show the form for editing the specified resource.
	*
	* @param  \App\ThreadReply  $threadReply
	* @return \Illuminate\Http\Response
	*/
	public function edit(ThreadReply $threadReply)
	{
	}

	/**
	* Update the specified resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  \App\ThreadReply  $threadReply
	* @return \Illuminate\Http\Response
	*/
	public function update(Request $request, ThreadReply $reply)
	{
		try {
			$this->authorize('update',$reply);
			$data = request()->validate([
				'body'=>'required|spamfree'
			]);
			$reply->update($data);
		} catch (\Exception $e) {
			return response('Sorry, we could not process your request',422);
		}
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  \App\ThreadReply  $threadReply
	* @return \Illuminate\Http\Response
	*/
	public function destroy(ThreadReply $reply)
	{
		$this->authorize('update',$reply);
		$body = $reply->delete();
		return response()->json(array('status'=>1,'fb'=>'Reply Deleted'));
	}
}
