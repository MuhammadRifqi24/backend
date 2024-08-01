<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Middleware as Mid;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: 'api/v1',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::prefix('api/v1/manager')->group(base_path('routes/roles/apiManager.php'));
            Route::prefix('api/v1/kasir')->group(base_path('routes/roles/apiKasir.php'));
            Route::prefix('api/v1/pelayan')->group(base_path('routes/roles/apiPelayan.php'));
            Route::prefix('api/v1/dapur')->group(base_path('routes/roles/apiDapur.php'));
            Route::prefix('api/v1/stan')->group(base_path('routes/roles/apiStan.php'));
            Route::prefix('api/v1/owner')->group(base_path('routes/roles/apiOwner.php'));
            Route::prefix('api/v1/user')->group(base_path('routes/roles/apiUser.php'));
            Route::prefix('api/v1/admin')->group(base_path('routes/roles/apiAdmin.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'checkRole' => Mid\CheckRole::class,
            'checkVerifyEmail' => Mid\CheckVerifyEmail::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
//        $exceptions->render(function (Throwable $exception, Request $request) {
//            if ($exception instanceof InvalidArgumentException) {
//                $code = 500;
//                $message = "kesalahan InvalidArgumentException, kirim email gagal ...";
//                return response()->json([
//                    'status' => false,
//                    'message' => $message,
//                    'result' => [
//                        'file' => $exception->getFile(),
//                        'line' => $exception->getLine(),
//                        'code' => $code,
//                        'message' => $exception->getMessage()
//                    ]
//                ], $code);
//            } else if ($exception instanceof MethodNotAllowedHttpException) {
//                $code = $exception->getStatusCode();
//                $message = "Method URL tidak diizinkan";
//                return response()->json([
//                    'status' => false,
//                    'message' => $message,
//                    'result' => [
//                        'file' => $exception->getFile(),
//                        'line' => $exception->getLine(),
//                        'code' => $code,
//                        'message' => $exception->getMessage()
//                    ]
//                ], $code);
//            } else if ($exception instanceof AuthenticationException) {
//                $code = Response::HTTP_UNAUTHORIZED;
//                $message = "Sesi token anda sudah habis, silahkan login ulang";
//                return response()->json([
//                    'status' => false,
//                    'message' => $message,
//                    'result' => [
//                        'file' => $exception->getFile(),
//                        'line' => $exception->getLine(),
//                        'code' => $code,
//                        'message' => $exception->getMessage()
//                    ]
//                ], $code);
//            } else if ($exception instanceof TypeError) {
//                $code = Response::HTTP_INTERNAL_SERVER_ERROR;
//                $message = "Jangan asal bro";
//                return response()->json([
//                    'status' => false,
//                    'message' => $message,
//                    'result' => [
//                        'file' => $exception->getFile(),
//                        'line' => $exception->getLine(),
//                        'code' => $code,
//                        'message' => $exception->getMessage()
//                    ]
//                ], $code);
//            } else if ($exception instanceof ArgumentCountError) {
//                $code = Response::HTTP_REQUEST_TIMEOUT;
//                $message = "Argument tidak lengkap";
//                return response()->json([
//                    'status' => false,
//                    'message' => $message,
//                    'result' => [
//                        'file' => $exception->getFile(),
//                        'line' => $exception->getLine(),
//                        'code' => $code,
//                        'message' => $exception->getMessage()
//                    ]
//                ], $code);
//            } else if ($exception instanceof NotFoundHttpException) {
//                $code = $exception->getStatusCode();
//                $message = "Route API tidak tersedia";
//                return response()->json([
//                    'status' => false,
//                    'message' => $message,
//                    'result' => [
//                        'file' => $exception->getFile(),
//                        'line' => $exception->getLine(),
//                        'code' => $code,
//                        'message' => $exception->getMessage()
//                    ]
//                ], $code);
//            }
//        });
    })->create();
