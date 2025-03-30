<?php

namespace App\Middleware;

use Framework\Request;

interface Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Framework\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next);
}
