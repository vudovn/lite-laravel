<?php

namespace App\Middleware;

use Framework\Request;

class RoleMiddleware implements Middleware
{
    protected $role;

    /**
     * Create a new role middleware instance.
     *
     * @param  string  $role
     * @return void
     */
    public function __construct($role)
    {
        $this->role = $role;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Framework\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        // Check if user is authenticated
        $user = auth();

        if (!$user) {
            toast_error('Please login to access this page');
            return redirect('/login');
        }

        // Check if user has the required role
        if ($user->role !== $this->role) {
            toast_error('You do not have permission to access this page');
            return redirect('/dashboard');
        }

        // Continue with the request
        return $next($request);
    }
}
