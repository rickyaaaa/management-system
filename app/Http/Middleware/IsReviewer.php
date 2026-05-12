<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsReviewer
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role_level == 3) {
            return $next($request);
        }
        abort(403, 'Unauthorized. Reviewer access only.');
    }
}
