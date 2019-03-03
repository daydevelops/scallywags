<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{

	use RecordsActivity;
	use Favourable;

	protected $guarded = [];

	protected static function boot() {
		parent::boot();
		static::addGlobalScope('replies_count', function ($builder) {
			$builder->withCount('replies');
		});
		static::addGlobalScope('category', function ($builder) {
			$builder->with('category');
		});
		static::addGlobalScope('user', function ($builder) {
			$builder->with('user');
		});
		static::deleting(function($thread) {
			$thread->replies->each->delete();
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
	public function subscriptions() {
		return $this->hasMany(ThreadSubscription::class);
	}

	public function addReply($reply) {
		return $this->replies()->create($reply);
	}
	public function subscribe() {
		$attributes = ['user_id' => auth()->id()];
		if (! $this->subscriptions()->where($attributes)->exists()) {
			return $this->subscriptions()->create($attributes);
		}
	}
	public function unsubscribe() {
		return $this->subscriptions()->where(['user_id' => auth()->id()])->delete();
	}

	public function getPath() {
		return "/forum/".$this->category->slug."/".$this->id;
	}

	public function scopeFilter($query, $filters) {
		return $filters->apply($query);
	}

}
