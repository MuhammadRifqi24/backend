<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckVerifyEmail
{
    use ApiResponser;

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->email_verified_at === null) {
            return $this->errorResponse([], 'Email Not Verified', 500);
        }
        return $next($request);
    }
}
