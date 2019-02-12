<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForumActivity extends Model
{
	protected $guarded = [];

	public function subject() {
		return $this->morphTo();
	}
}
