<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\Filter;
use App\Thread;
use App\User;

class ThreadFilter extends Filter {

	protected $filters = ['u'];

	public function u($user_id) {
		
		return $this->builder->where('user_id',$user_id);
	}

}