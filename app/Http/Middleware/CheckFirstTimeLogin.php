<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckFirstTimeLogin
{

    protected $exceptRoutes = [
        'force.change.password'
    ];

    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
 
        $route = $request->route()->getName();

        if(!Auth::check() && ($request->route()->getPrefix() == 'logged-in' )){
            return redirect()->route('login');
        }
        
        if($route == "change.password"){
            return $next($request);
        }
        if($route == "logout"){
            return $next($request);
        }
        
        if(!in_array($route, $this->exceptRoutes)) {
            if(!Auth::check()){
                return redirect()->route('login');
            }
            if (Auth::user()->is_new_account == 1 ) {
                return redirect()->route('force.change.password');
            }
        }

        return $next($request);

        }

}
