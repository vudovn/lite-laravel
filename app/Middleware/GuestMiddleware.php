<?php

namespace App\Middleware;

use Framework\Request;
use Framework\Response;

class GuestMiddleware implements Middleware
{
    /**
     * Handle an incoming request.
     * Prevent logged-in users from accessing guest-only pages (like login/register)
     *
     * @param  \Framework\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        if (auth()) {
            toast_info('You are already logged in');
            return redirect('/dashboard');
        }
        return $next($request);
    }
}
