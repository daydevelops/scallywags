<?php
namespace App\Inspections;

class InvalidKeywords {
	protected $keywords = [
		'Yahoo Customer Support'
	];
	public function detect($str) {
		foreach ($this->keywords as $kw) {
			if (strpos($str,$kw) !== false) {
				throw new \Exception('Spam Detected');
			}
		}
	}
}
