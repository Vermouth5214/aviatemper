<?php

namespace App\Http\Middleware;

use Cache;
use Closure;
use Session;

class TokenITTirtaMiddleware
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
        if(!Session::get('userinfo')) {
            return redirect('/backend/login');
        } else{
            $userinfo = Session::get('userinfo');
            //Jika bukan admin
        	if (($userinfo['priv'] != 'VSUPER') && ($userinfo['priv'] != 'VTTIRTA')){
                return redirect('/');
            }
        }
        return $next($request);
    }
}