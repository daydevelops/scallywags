<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class RedirectIfMailNotConfirmed
{
	/**
	* Handle an incoming request.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  \Closure  $next
	* @return mixed
	*/
	public function handle($request, Closure $next)
	{
		if (!auth()->user()->email_verified_at) {
			Session::flash('message','You must first confirm your email address before contributing to the forum');
			Session::flash('alert-type','alert-danger');
			return back()->with('flash');
		}
		return $next($request);
	}
}
