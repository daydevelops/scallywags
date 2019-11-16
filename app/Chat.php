<?php

namespace App;

use App\User;
use App\Message;
use App\Events\NewMessage;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['channel_name'];
    protected $appends = ['friend'];

    protected static function boot() {
		parent::boot();
		static::addGlobalScope('users', function ($builder) {
			$builder->with('users');
		});
		static::addGlobalScope('messages', function ($builder) {
			$builder->with('messages');
		});
	}

    public function users() {
        return $this->belongsToMany(User::class);
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }

    public function getFriendAttribute() {
        return $this->friend();
    }

    public function friend() {
        // returns the first user in convo->users that the authed user is talking to
        return $this->users->first(function($u) {
            return $u->id !== auth()->id();
        });
    }

    public function isUser($user_id) {
       return  $this->users()->where(['user_id'=>$user_id])->exists();
    }

    public function addMessage($msg) {
        $message = $this->messages()->create($msg);
		event(new NewMessage($message));
    }

    public static function startNew($data) {
        // create the chat
        $chat = Chat::create();
        $chat->update(['channel_name'=>'chat_'.$chat->id]);
        
        // add the users
        $chat->users()->attach($data['user_ids']);
        $chat->users()->attach(auth()->id());

        // log the first message
        $chat->addMessage([
            'body'=>$data['message'],
            'user_id'=>auth()->id(),
            'chat_id'=>$chat->id
        ]);

        return $chat->fresh();
    }
}
