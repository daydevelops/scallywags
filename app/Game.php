<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'gamedate','private','admin-created','event_id'
    ];

    public function users() {
        return $this->belongsToMany(User::class)->wherePivot('confirmed','1');
    }
    public function usersPublic() {
        return $this->belongsToMany(User::class)->wherePivot('confirmed','1')->select(['name','skill','image']);
    }

    public function invites() {
        return $this->belongsToMany(User::class)->wherePivot('confirmed','0');
    }
    public function InvitesPublic() {
        return $this->belongsToMany(User::class)->wherePivot('confirmed','0')->select(['name','skill','image']);
    }

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function join() {
        if (!$this->isPlaying() && !$this->isInvited()) { // if user decided to join without invitation
            if ($this->private) {
                return array('status'=>0,'fb'=>'You are not allowed to join this private game');
            }
            GameUser::create([
                'game_id'=>$this->id,
                'user_id'=>auth()->id(),
                'confirmed'=>1
            ]);
        } else if ($this->isInvited()) { // if user is accepting invitation
            GameUser::where('user_id',auth()->user()->id)->where('game_id',$this->id)->update(['confirmed'=>1]);
        }
        return array('status'=>1,'fb'=>'You have joined this game');
    }

    public function leave() {
        if ($this->isPlaying() || $this->isInvited()) {
            // remove row in game_users table
            GameUser::where('user_id',auth()->user()->id)->where('game_id',$this->id)->delete();
            if (sizeof($this->users)==0 && sizeof($this->invites)==0) {
                // no one else is associated with this game, delete it
                $event = $this->event;
                $this->delete();
                if (sizeof($event->games)==0) {
                    // no other games for this event, delete it.
                    $event->delete();
                }
            }
            return array('status'=>1,'fb'=>'You have left this game');
        } else {
            return array('status'=>0,'fb'=>'You are not playing this game');
        }

    }

    public function invite($user_id) {
        GameUser::create([
            'game_id'=>$this->id,
            'user_id'=>$user_id,
            'confirmed'=>0
        ]);

    }

    public function isPlaying() {
        // check if user has already joined this game
        return $this->users()->where('user_id',auth()->id())->count() > 0;
    }

    public function isInvited() {
        return $this->invites()->where('user_id',auth()->id())->count() > 0;
    }

    public function makePrivate() {
        $this->update(['private'=>1]);
    }
    public function makePublic() {
        $this->update(['private'=>0]);
    }

}
