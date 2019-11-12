<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;
use App\Favourite;
use App\Message;
use App\Conversation;

class User extends Authenticatable implements MustVerifyEmail
{
	use Notifiable;
	use Favourable;
	use Reputation;

	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
		'name', 'email', 'password','skill','image','email_verified_at'
	];

	/**
	* The attributes that should be hidden for arrays.
	*
	* @var array
	*/
	protected $hidden = [
		'password', 'remember_token',
	];

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

	public function lastReply() {
		return $this->hasOne(ThreadReply::class)->latest();
	}

	public function activities() {
		return $this->hasMany(ForumActivity::class);
	}

	public function subscriptions() {
		return $this->hasMany(ThreadSubscription::class);
	}

	public function messages() {
		return $this->hasMany(Message::class);
	}

	public function conversations() {
		return $this->belongsToMany(Conversation::class);
	}

	public static function allPublic() {
		return User::select('id','name','skill','image')->get();
	}

	public function read($thread_id) {
		$key = $this->visitedThreadCachedKey($thread_id);
		cache()->forever($key,Carbon::now());
	}

	public function visitedThreadCachedKey($thread_id) {
		return sprintf("user.%s.visits.%s",auth()->id(),$thread_id);
	}

	public function getPath() {
		return "/profile/".$this->id;
	}

	public function favourites() {
		return $this->hasMany(Favourite::class)->with('favourited')->get();
	}
}
