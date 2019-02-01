<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThreadReply extends Model
{

	protected $guarded = [];

	public function user() {
		return $this->belongsTo(User::class);
	}
	public function thread() {
		return $this->belongsTo(Thread::class);
	}
	public function favourites() {
		return $this->morphMany(Favourite::class,'favourited');
	}


	public function favourite() {
		$attributes = ['user_id'=>auth()->id()];
		if (! $this->favourites()->where($attributes)->exists()) {
			$this->favourites()->create($attributes);
		}
	}
}
