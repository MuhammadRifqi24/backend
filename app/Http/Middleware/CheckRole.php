<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    use ApiResponser;

    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (in_array($request->user()->user_level->role, $roles)) {
            return $next($request);
        }
        return $this->errorResponse([], 'Anda tidak memiliki hak akses', 500);
    }
}
