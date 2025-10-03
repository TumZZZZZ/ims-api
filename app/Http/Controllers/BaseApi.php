<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class BaseApi extends Controller
{
    /**
     * Success response
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResponse($data, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'status'    => true,
            'code'      => $code,
            'message'   => $message,
            'data'      => $data,
        ], $code);
    }

    /**
     * Error response
     *
     * @param string $message
     * @param int $code
     * @param array|null $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendError(string $message, int $code = 400, ?array $errors = null): JsonResponse
    {
        $response = [
            'status'  => false,
            'code'    => $code,
            'message' => $message,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}
