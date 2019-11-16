<?php

namespace App;
use App\User;
use App\Chat;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['user_id','chat_id','body'];

    protected static function boot() {
		parent::boot();
		static::addGlobalScope('user', function ($builder) {
			$builder->with('user');
		});
	}

	public function user() {
		return $this->belongsTo(User::class);
	}
}
