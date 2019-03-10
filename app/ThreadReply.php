<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThreadReply extends Model
{
	use RecordsActivity;
	use Favourable;

	protected $fillable = ['thread_id','user_id','body'];
	protected $appends = array('is_favourited');
	protected $touches = ['Thread'];

	protected static function boot() {
		parent::boot();
		static::addGlobalScope('user', function ($builder) {
			$builder->with('user');
		});
		static::addGlobalScope('thread', function ($builder) {
			$builder->with('thread');
		});
		static::deleting(function($thread) {
			auth()->user()->read($thread->id);
		});
		static::saving(function($thread) {
			auth()->user()->read($thread->id);
		});
		// static::updating(function($thread) {
		// 	auth()->user()->read($thread->id);
		// });
	}

	public function user() {
		return $this->belongsTo(User::class);
	}
	public function thread() {
		return $this->belongsTo(Thread::class);
	}

}
