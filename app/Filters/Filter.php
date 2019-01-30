<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Thread;

abstract class Filter {

	protected $request;
	protected $builder;
	protected $filters = [];

	public function __construct(request $request) {
		$this->request = $request;
	}

	public function apply($builder) {
		$this->builder = $builder;
		// loop through all filters and apply them
		foreach($this->getFilters() as $filter=>$value) {
			// if we have a method in the child class for this kind of filter
			if (method_exists($this,$filter)) {
				$this->$filter($value);
			}
		}
		return $this->builder;
		// if (!$this->request->u) return $this->builder;
		// return $this->by($this->request->u);
	}

	public function getFilters() {
		// return key value pairs from request data with appropriate keys
		return $this->request->only($this->filters);
	}
}
