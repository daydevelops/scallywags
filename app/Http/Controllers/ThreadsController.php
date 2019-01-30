<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Category;
use App\Filters\ThreadFilter;
use Illuminate\Http\Request;

class ThreadsController extends Controller
{
	public function __construct() {
		$this->middleware('auth')->except(['index','show']);
	}
	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index(Category $category, ThreadFilter $filters)
	{
		if ($category->exists) {
			$threads = $category->threads()->latest();
		} else {
			$threads = Thread::latest();
		}
		$threads = $threads->filter($filters)->get();
		$categories = Category::all();
		return view('forum/index',compact('threads','categories'));
	}

	/**
	* Show the form for creating a new resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function create()
	{
		$categories = Category::all();
		return view('forum/new',compact('categories'));
	}

	/**
	* Store a newly created resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @return \Illuminate\Http\Response
	*/
	public function store(Request $request)
	{
		// dd($request);
		$data = request()->validate([
			'title'=>'required',
			'body'=>'required',
			'category_id'=>'required|exists:categories,id'
		]);
		$data['user_id'] = auth()->user()->id;

		$thread = Thread::create($data);

		return redirect($thread->getPath());
	}

	/**
	* Display the specified resource.
	*
	* @param  \App\Thread  $thread
	* @return \Illuminate\Http\Response
	*/
	public function show($category_id, Thread $thread)
	{
		$replies = $thread->replies;
		return view('forum/show',compact('thread','replies'));
	}

	/**
	* Show the form for editing the specified resource.
	*
	* @param  \App\Thread  $thread
	* @return \Illuminate\Http\Response
	*/
	public function edit(Thread $thread)
	{
		//
	}

	/**
	* Update the specified resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  \App\Thread  $thread
	* @return \Illuminate\Http\Response
	*/
	public function update(Request $request, Thread $thread)
	{
		//
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  \App\Thread  $thread
	* @return \Illuminate\Http\Response
	*/
	public function destroy(Thread $thread)
	{
		//
	}
}
