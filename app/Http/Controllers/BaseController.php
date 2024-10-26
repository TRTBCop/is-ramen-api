<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @param $result
     * @param $message
     * @return JsonResponse
     */
    public function sendResponse($result, $message): JsonResponse
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];

        $headers = [
            'app-latest-release' => config('dokdo.app_latest_release') ?? '',
        ];

        return response()->json($response, 200, $headers);
    }

    /**
     * return error response.
     *
     * @param $error
     * @param array|string $errorMessages
     * @param int $code
     * @return JsonResponse
     */
    public function sendError($error, array | string $errorMessages = [], int $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code ?: 500);
    }
}
