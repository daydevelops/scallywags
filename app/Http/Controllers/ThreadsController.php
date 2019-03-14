<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Category;
use App\Filters\ThreadFilter;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

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

		$trending_thread = $this->getTrending();

		if (request()->wantsJson()) {
			return $threads;
		}
		// echo json_encode($threads);
		// return null;
		return view('forum/index',compact('threads','categories','trending_thread'));
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
			'title'=>'required|spamfree',
			'body'=>'required|spamfree',
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
		if (auth()->check()) {
			auth()->user()->read($thread->id);
		}
		Redis::zincrby('trending_threads',1,json_encode([
			'title'=>$thread->title,
			'path'=>$thread->getPath()
		]));
		$trending_thread = $this->getTrending();
		$page = $request->page?$request->page:1;
		return view('forum/show',compact('thread','page','trending_thread'));
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

	protected function getTrending() {
		$threads = Redis::zrevrange('trending_threads',0,-1);
		return json_decode($threads[0]);
	}
}
