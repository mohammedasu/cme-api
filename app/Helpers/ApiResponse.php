<?php

namespace App\Helpers;

use App\Constants\Constants;
use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * Defined success response format
     * @param string $message
     * @param null $response
     * @param int $code
     * @return JsonResponse
     */
    public static function successResponse(string $message=Constants::RESPONSE_SUCCESS_MESSAGE, $response = null, int $code = Constants::SUCCESS_RESPONSE_CODE): JsonResponse
    {
        return response()->json([
        'status' => true,
        'message' => $message,
        'response' => $response
        ], $code);
    }

    /**
     * Defined failure response format
     * @param string $message
     * @param null $response
     * @param int $code
     * @return JsonResponse
     */
    public static function failureResponse(string $message=Constants::RESPONSE_ERROR_MESSAGE, $response = null, int $code = Constants::INTERNAL_SERVER_ERROR): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'response' => $response
        ], $code);
    }

    /**
     * Defined validation response
     * @param string $message
     * @param null $response
     * @param int $code
     * @return JsonResponse
     */
    public static function validationFailure(string $message=Constants::RESPONSE_ERROR_MESSAGE, $response = null, int $code = Constants::VALIDATION_RESPONSE_CODE): JsonResponse
    {
        return response()->json([
        'status' => false,
        'message' => $message,
        'response' => $response
        ], $code);
    }

    public static function customArrayResponse($status, $code, $message, $response): array
    {
        return [
        'status'    => $status,
        'code'      => $code,
        'message'   => $message,
        'response'  => $response
        ];
    }
}
