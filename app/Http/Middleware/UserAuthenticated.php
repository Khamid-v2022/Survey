<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Config;

use Illuminate\Support\Facades\Route;

class UserAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if( Auth::check() )
        {
            /** @var User $user */
            $user = Auth::user();
            
            // if user is not admin take him to his dashboard
            $userrole = $user->role;
         
            if ( $user->hasRole('Admin') ) {
                return redirect('/admin_dashboard');
            }
            // allow admin to proceed with request
            else if ( in_array($userrole, Config::get('constants.roles.user')) ) {
                return $next($request);
            }
        }

        abort(403);  // permission denied error
    }
}