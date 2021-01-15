<?php namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Route;

class AuthenticateAdmin {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param null $role
     * @return mixed
     */
	public function handle($request, Closure $next, $pagePermission = null)
	{
		if (Auth::guest()) {
            return redirect()->guest(route('login'));
        }

        if (! Auth::user()->hasPagePermission($pagePermission)) {
            return redirect()->to('webadmin');
        }
        
        return $next($request);
	}
}
