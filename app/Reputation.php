<?php
namespace App;

trait Reputation {

    protected $reputation_events = [
        'created_thread' => 1,
        'created_reply' => 1,
        'deleted_thread' => 1,
        'deleted_reply' => 1,
        'received_reply' => 1,
        'lost_reply' => 1,
        'favourited' => 3,
        'unfavourited' => 3,
        'received_best_reply' => 10,
    ];

    public function award($event) {
        $this->increment('reputation',$this->reputation_events[$event]);
    }
    
    public function unaward($event) {
        if ($this->reputation < $this->reputation_events[$event]) {
            // bring the reputation to a minimum of zero
            $this->decrement('reputation',$this->reputation);
        } else {
            $this->decrement('reputation',$this->reputation_events[$event]);
        }
    }
}