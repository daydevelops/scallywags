<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use DateInterval;

class Event extends Model
{
    protected $fillable = ['title','start','end'];

    public function games() {
        return $this->hasMany(Game::class);
    }

    static public function bookCourt($time) {
        // is there already an event for the same timeslot?
        $event = Event::where('start',$time)->get();
        if (sizeof($event)>0) {
            // does this event have two games already?
            $event = $event[0];
            if (sizeof($event->games)>1) {
                return 0;
            } else {
                $event->update(['title'=>'2 Courts Booked']);
                return $event->id;
            }
        } else {
            // create new event
            $endTime = new DateTime($time);
            $endTime->add(new DateInterval('PT40M')); // 40 minutes later
            $evt = Event::create([
                'title'=>'1 Court Booked',
                'start'=>$time,
                'end'=>$endTime->format('c')
            ]);
            return $evt->id;
        }
    }

}
