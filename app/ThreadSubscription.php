<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThreadSubscription extends Model
{
	protected $guarded = [];
	
    public $incrementing = true;

	public function user() {
		return $this->belongsTo(User::class);
	}

	public function thread() {
		return $this->belongsTo(Thread::class);
	}
}
