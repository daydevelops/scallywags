<?php
namespace App\Inspections;

class Spam {
	protected $inspections = [
		InvalidKeywords::class,
		KeyHeldDown::class,
	];
	public function detect($str) {
		foreach($this->inspections as $i) {
			app($i)->detect($str);
		}
		return false;
	}
}
