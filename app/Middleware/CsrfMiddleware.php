<?php

namespace App\Middleware;

use Framework\Request;
use Framework\Response;

class CsrfMiddleware implements Middleware
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
        // Skip for GET requests
        if ($request->getMethod() === 'GET') {
            return $next($request);
        }

        // Verify CSRF token
        $token = $request->get('_token');
        if (!$token || $token !== $_SESSION['_token'] ?? '') {
            return new Response('CSRF token mismatch', 419);
        }

        // Continue with the request
        return $next($request);
    }
}
