<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

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

    protected function responseSuccess($data, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'status'    => true,
            'code'      => $code,
            'message'   => $message,
            'data'      => $data,
        ], $code);
    }

    protected function responseError($e)
    {
        // Default values
        $status  = 500;
        $message = 'Internal server error';
        $code    = null;

        // Authentication failed
        if ($e instanceof AuthenticationException) {
            $status  = 401;
            $message = $e->getMessage() ?: 'Unauthorized';
        }

        // HTTP exceptions (abort(), HttpResponseException, etc.)
        elseif ($e instanceof HttpExceptionInterface) {
            $status  = $e->getStatusCode();
            $message = $e->getMessage() ?: 'Request error';
        }

        // Custom exception with status
        elseif (method_exists($e, 'getStatusCode')) {
            $status = $e->getStatusCode();
            $message = $e->getMessage();
        }

        // App debug: expose real message
        if (config('app.debug')) {
            $message = $e->getMessage();
            $code    = $e->getCode();
        }

        return response()->json([
            'success' => false,
            'message' => $message,
            'code'    => $code,
        ], $status);
    }
}
