<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
	use RecordsActivity;

	protected static function boot() {
		parent::boot();
		// static::addGlobalScope('favourited', function ($builder) {
		// 	$builder->with('favourited');
		// });
	}
    protected $guarded = [];

	public function favourited() {
		return $this->morphTo()->withoutGlobalScopes();
	}
}
