<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function successResponse($data , string $message = 'Operation successful', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    protected function errorResponse(string $message, int $statusCode = 500): JsonResponse
    {
        return response()->json([
            'success' => false,
            'status' => 'error',
            'message' => $message,
        ], $statusCode);
    }
    protected function badRequestResponse(string $message, int $statusCode = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'status' => 'bad request',
            'message' => $message,
        ], $statusCode);
    }
    protected function forbiddenResponse(string $message = 'Forbidden', int $statusCode = 403): JsonResponse
    {
        return response()->json([
            'success' => false,
            'status' => 'forbidden',
            'message' => $message,
        ], $statusCode);
    }
}