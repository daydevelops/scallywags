<?php
namespace App;

trait RecordsActivity {

	protected static function bootRecordsActivity() {

		static::created(function($item) {
			$item->recordActivity('created');
		});
	}

	public function recordActivity($event) {
		$this->activity()->create([
			'user_id'=>$this->user_id,
			'type'=>$event.'_'.strtolower(class_basename($this)),
		]);
	}

	public function activity() {
		return $this->morphMany('App\ForumActivity','subject');
	}
}
