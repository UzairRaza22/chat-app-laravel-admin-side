<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

/**
 * Response Service Provider
 *
 * Registers custom response macros for consistent API responses
 *
 * @method \Illuminate\Http\JsonResponse success(string $message = "Success", mixed $data = null, int $code = 200)
 * @method \Illuminate\Http\JsonResponse validationError(string $message = "Validation Failed", array $errors = [])
 * @method \Illuminate\Http\JsonResponse unauthorized(string $message = "Unauthorized")
 * @method \Illuminate\Http\JsonResponse forbidden(string $message = "Forbidden")
 * @method \Illuminate\Http\JsonResponse notFound(string $message = "Resource Not Found")
 */
class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Success response macro
        Response::macro('success', function ($message = "Success", $data = null, $code = 200) {

            if ($data === null) {
                return Response::json([
                    'success' => true,
                    'message' => $message
                ], $code);
            }

            return Response::json([
                'success' => true,
                'message' => $message,
                'data' => $data
            ], $code);
        });

        // Validation error response macro
        Response::macro('validationError', function ($message = "Validation Failed", $errors = []) {
            return Response::json([
                'success' => false,
                'message' => $message,
                'errors' => $errors
            ], 422);
        });

        // Unauthorized response macro
        Response::macro('unauthorized', function ($message = "Unauthorized") {

            return Response::json([
                'success' => false,
                'message' => $message,
                'errors' => null,
                'data' => null
            ], 401);

        });

        // Forbidden response macro
        Response::macro('forbidden', function ($message = "Forbidden") {

            return Response::json([
                'success' => false,
                'message' => $message,
                'errors' => null,
                'data' => null
            ], 403);
        });

        // Not found response macro
        Response::macro('notFound', function ($message = "Resource Not Found") {

            return Response::json([
                'success' => false,
                'message' => $message,
                'errors' => null,
                'data' => null
            ], 404);
        });
    }

}
