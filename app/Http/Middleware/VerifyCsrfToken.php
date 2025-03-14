<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier {

 //	protected $except = [
 //        'api/*' // Isključuje CSRF proveru za sve rute
 //    ];
    
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */


	public function handle($request, Closure $next)
	{
		return parent::handle($request, $next); // Comment this line
    	// return $next($request); // Bypass CSRF check
	}

}
