<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (Throwable $e, $request) {
            // Jika request ke API, balikin JSON response
            if ($request->is('api/*')) {
                $status = 500;
                $message = 'Server Error';

                if ($e instanceof NotFoundHttpException) {
                    $status = 404;
                    $message = 'Resource not found';
                } elseif ($e instanceof HttpException) {
                    $status = $e->getStatusCode();
                    $message = $e->getMessage() ?: 'HTTP Error';
                } else {
                    $message = $e->getMessage() ?: $message;
                }

                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], $status);
            }
        });
    }
}
