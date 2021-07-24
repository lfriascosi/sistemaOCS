<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Session;
use Redirect;

class AccesControl
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
        if(Session::has('user_array')){
            return Redirect::to('/main');
        }else{
            return $next($request);
        }
        //////////////////////////////////////////////////////////////////
        // if($request->session()->get('user_array') == null){
        //     return $next($request);
        // }
        
        // if($request->session()->get('user_array') != null){
        //     $user_name = $request->session()->get('user_array.0.apellidos');
        //     $capsule = array('user_name'=>$user_name);
        //     return redirect('/main')->with($capsule);
        // }
    }
}
