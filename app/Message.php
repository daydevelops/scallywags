<?php

namespace App;
use App\User;
use App\Chat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Message extends Model
{
	protected $fillable = ['user_id','chat_id','body'];
	
	protected $appends = ['hasViewed'];

    protected static function boot() {
		parent::boot();
		static::addGlobalScope('user', function ($builder) {
			$builder->with('user');
		});
	}

	public function user() {
		return $this->belongsTo(User::class);
	}

	public function chat() {
		return $this->belongsTo(Chat::class);
	}

	public function viewed() {

		if ($this->hasViewed) {return null;}

		DB::table('message_views')->insert([
			'message_id'=>$this->id,
			'user_id'=>auth()->id()
		]);
		
	}

	public function views() {
		return DB::table('message_views')->where(['message_id'=>$this->id])->get();
	}

	public function hasViewed($user_id) {
		return DB::table('message_views')->where([
			'message_id'=>$this->id,
			'user_id'=>$user_id
		])->exists();
	}

	public function getHasViewedAttribute() {
		return $this->hasViewed(auth()->id());
	}
}
