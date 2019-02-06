<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{

	use RecordsActivity;
	
	protected $guarded = [];

	protected static function boot() {
		parent::boot();
		static::addGlobalScope('replies_count', function ($builder) {
			$builder->withCount('replies');
		});
		static::addGlobalScope('category', function ($builder) {
			$builder->with('category');
		});
		static::addGlobalScope('favourites', function ($builder) {
			$builder->with('favourites');
		});
		static::addGlobalScope('user', function ($builder) {
			$builder->with('user');
		});

		static::deleting(function($thread) {
			$thread->replies()->delete();
		});

	}


	public function replies() {
		return $this->hasMany(ThreadReply::class);
	}
	public function user() {
		return $this->belongsTo(User::class);
	}
	public function category() {
		return $this->belongsTo(Category::class);
	}
	public function favourites() {
		return $this->morphMany(Favourite::class,'favourited');
	}

	public function addReply($reply) {
		$this->replies()->create($reply);
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
		return $this->favourites->where(['user_id'=>auth()->id()])->count() > 0;
	}
	public function getPath() {
		return "/forum/".$this->category->slug."/".$this->id;
	}

	public function scopeFilter($query, $filters) {
		return $filters->apply($query);
	}

}
