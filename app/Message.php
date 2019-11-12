<?php

namespace App;
use App\User;
use App\Conversation;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['user_id','conversation_id','body'];
}
