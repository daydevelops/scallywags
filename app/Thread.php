<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
	public function replies() {
        return $this->hasMany(ThreadReply::class);
    }
	public function user() {
		return $this->belongsTo(User::class);
	}
}
