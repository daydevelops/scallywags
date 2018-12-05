<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameUser extends Model
{
    protected $table = 'game_user';
    protected $fillable = ['game_id','user_id','confirmed'];

    public function game() {
        return $this->belongsTo(Game::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
