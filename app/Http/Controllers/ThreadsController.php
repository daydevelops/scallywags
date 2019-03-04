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
			$threads = $category->threads();
			$threads = $threads->filter($filters)->latest()->paginate(10);
		} else {
			$threads = Thread::filter($filters)->paginate(10);
		}

		$categories = Category::all();

		if (request()->wantsJson()) {
			return $threads;
		}
		// echo json_encode($threads);
		// return null;
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
		$thread->subscribe();
		return redirect($thread->getPath());
	}

	/**
	* Display the specified resource.
	*
	* @param  \App\Thread  $thread
	* @return \Illuminate\Http\Response
	*/
	public function show(Request $request, $category_id, Thread $thread)
	{
		$page = $request->page?$request->page:1;
		// $replies = $thread->replies()->paginate(10);
		// return $thread;
		return view('forum/show',compact('thread','page'));
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
	public function destroy($category, Thread $thread)
	{
		$this->authorize('update',$thread);
		$thread->delete();
		return response()->json(array('status'=>1,'fb'=>'Thread Deleted'));
	}
}
