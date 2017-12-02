<?php

namespace App\Http\Middleware;

use App\Admin;
use Closure;

class IsAdmin
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
        if ($request->user() && Admin::check($request->user())) {
            return $next($request);
        }
        
        throw new AuthorizationException('This action is unauthorized.', 403);
    }
}
