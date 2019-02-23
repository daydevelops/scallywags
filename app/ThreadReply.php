<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThreadReply extends Model
{
	use RecordsActivity;
	use Favourable;

	protected $guarded = [];
	protected $appends = array('is_favourited');

	protected static function boot() {
		parent::boot();
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

}
