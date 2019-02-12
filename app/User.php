<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use Notifiable;

	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
		'name', 'email', 'password','skill','image'
	];

	/**
	* The attributes that should be hidden for arrays.
	*
	* @var array
	*/
	protected $hidden = [
		'password', 'remember_token',
	];


	public function games() {
		return $this->belongsToMany(Game::class)->wherePivot('confirmed','1');
	}

	public function invites() {
		return $this->belongsToMany(Game::class)->wherePivot('confirmed','0');
	}

	public function threadsWithGS() {
		return $this->hasMany(Thread::class);
	}

	public function repliesWithGS() {
		return $this->hasMany(ThreadReply::class);
	}

	public function threads() {
		return $this->hasMany(Thread::class)->withoutGlobalScopes();
	}

	public function replies() {
		return $this->hasMany(ThreadReply::class)->withoutGlobalScopes();
	}

	public function activities() {
		return $this->hasMany(ForumActivity::class);
	}

	public static function allPublic() {
		return User::select('id','name','skill','image')->get();
	}
}
