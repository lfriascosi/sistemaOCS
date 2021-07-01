<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AutentificarOCS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // if($request->session()->get('user_id')=="" || $request->session()->get('user_name')==""){
        if($request->session()->get('user_array')==""){
            return redirect('/');
        }else{
            return $next($request);
        }
        
    }
}
