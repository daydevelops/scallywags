<?php

namespace app;

use Illuminate\Support\Facades\Redis;

class Trending {

	protected static function getCacheKey() {
		return app()->environment('testing') ? "trending_threads_test" : "trending_threads";
	}

	public static function push($thread) {
		Redis::zincrby(Trending::getCacheKey(),1,json_encode([
			'title'=>$thread->title,
			'path'=>$thread->getPath()
		]));
	}

	public static function get($index=2) {
		$threads = Redis::zrevrange(Trending::getCacheKey(),0,$index);
		return array_map(function($thread) {
			return json_decode($thread);
		},$threads);
	}
}
