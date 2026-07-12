<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponseTrait
{
    protected function successResponse(string $message, mixed $data, int $status = Response::HTTP_OK): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function errorResponse(string $message, int $status = Response::HTTP_BAD_REQUEST): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}
