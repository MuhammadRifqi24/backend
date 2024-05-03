<?php

use App\Http\Middleware as Mid;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: 'api/v1',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::prefix('api/v1/manager')->group(base_path('routes/roles/apiManager.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'checkRole' => Mid\CheckRole::class,
            'checkVerifyEmail' => Mid\CheckVerifyEmail::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
