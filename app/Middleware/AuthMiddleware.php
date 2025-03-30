<?php

namespace App\Middleware;

use Framework\Request;
use Framework\Response;

class AuthMiddleware implements Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Framework\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        // Check if user is authenticated using auth() helper
        if (!auth()) {
            // Store intended URL to redirect back after login
            $_SESSION['intended_url'] = $request->getUri();

            // Add flash message
            toast_error('Please login to access this page');

            // Redirect to login page
            return redirect('/login');
        }

        // Continue with the request
        return $next($request);
    }
}
