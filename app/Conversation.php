<?php

namespace App;

use App\User;
use App\Message;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['channel_name'];

    public function users() {
        return $this->belongsToMany(User::class);
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }
}
