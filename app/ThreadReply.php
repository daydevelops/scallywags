<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThreadReply extends Model
{
	use RecordsActivity;

	protected $guarded = [];

	protected static function boot() {
		parent::boot();
		static::addGlobalScope('favourites', function ($builder) {
			$builder->with('favourites');
		});
		static::addGlobalScope('user', function ($builder) {
			$builder->with('user');
		});
		static::addGlobalScope('thread', function ($builder) {
			$builder->with('thread');
		});
	}


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
	public function unfavourite() {
		$attributes = ['user_id'=>auth()->id()];
		if ($this->favourites()->where($attributes)->exists()) {
			$this->favourites()->where($attributes)->delete();
		}
	}

	public function isFavourited() {
		return $this->favourites()->where(['user_id'=>auth()->id()])->exists();
	}

	public function clear() {
		$this->update(['body'=>'Reply deleted','deleted'=>1]);
	}
}
