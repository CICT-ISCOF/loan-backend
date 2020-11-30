<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForAdministratorsOnly
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
        $user = $request->user();
        if (!$user || $user->role !== 'Super Admin') {
            return response('', 403);
        }
        return $next($request);
    }
}
