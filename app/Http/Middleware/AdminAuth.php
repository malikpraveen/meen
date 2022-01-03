<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\Admin;
use Request;


class AdminAuth
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
        
        $user=Auth::guard('admin')->check();
//         dd($user);
        if(Auth::guard('admin')->check() == false){
            return redirect('admin/login');
        }
        // $auth = Auth::id();
        // if(!empty($auth)){
        //     $user = User::find($auth);
        //     if($user['type'] != 'admin'){
        //         return redirect()->intended('admin/login');
        //     }
        // }

        
        return $next($request);
    }
}
