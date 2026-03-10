<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

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
        // Success Response Macro
        Response::macro('success', function ($message = 'Operation successful', $data = null, $code = 200) {
            $response = [
                'success' => true,
                'message' => $message,
            ];

            if ($data !== null) {
                $response['data'] = $data;
            }

            return response()->json($response, $code);
        });

        // Error Response Macro
        Response::macro('error', function ($message = 'Operation failed', $errors = null, $code = 400) {
            $response = [
                'success' => false,
                'message' => $message,
            ];

            if ($errors !== null) {
                $response['errors'] = $errors;
            }

            return response()->json($response, $code);
        });

        // Validation Error Response Macro
        Response::macro('validationError', function ($message = 'Validation failed', $errors = [], $code = 422) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'errors' => $errors,
            ], $code);
        });

        // Unauthorized Response Macro
        Response::macro('unauthorized', function ($message = 'Unauthorized access') {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 401);
        });

        // Forbidden Response Macro
        Response::macro('forbidden', function ($message = 'Access forbidden') {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 403);
        });

        // Not Found Response Macro
        Response::macro('notFound', function ($message = 'Resource not found') {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 404);
        });

        // Server Error Response Macro
        Response::macro('serverError', function ($message = 'Internal server error') {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 500);
        });

        // Created Response Macro
        Response::macro('created', function ($message = 'Resource created successfully', $data = null) {
            $response = [
                'success' => true,
                'message' => $message,
            ];

            if ($data !== null) {
                $response['data'] = $data;
            }

            return response()->json($response, 201);
        });

        // No Content Response Macro
        Response::macro('noContent', function ($message = 'No content available') {
            return response()->json([
                'success' => true,
                'message' => $message,
            ], 204);
        });
    }
}