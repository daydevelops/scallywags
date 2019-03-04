<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\ThreadRepliedTo;

class Thread extends Model
{

	use RecordsActivity;
	use Favourable;

	protected $guarded = [];
	protected $appends = array('is_subscribed');

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
		$reply = $this->replies()->create($reply);
		foreach($this->subscriptions as $sub) {
			if ($reply->user_id !== auth()->id()) { //no need to notify the user leaving the reply
				$sub->user->notify(new ThreadRepliedTo($this,$reply));
			}
		}
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


	/**
	* Determine if the current reply has been favourited.
	*
	* @return boolean
	*/
	public function isSubscribed()
	{
		return ! ! $this->subscriptions->where('user_id', auth()->id())->count();
	}
	/**
	* Fetch the favourited status as a property.
	*
	* @return bool
	*/
	public function getIsSubscribedAttribute()
	{
		return $this->isSubscribed();
	}

}
