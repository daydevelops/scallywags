<?php
namespace App\Inspections;

class KeyHeldDown {
	public function detect($str) {
		if (preg_match('/(.)\\1{4,}/',$str)) {
			throw new \Exception('Spam Detected');
		}
	}
}
