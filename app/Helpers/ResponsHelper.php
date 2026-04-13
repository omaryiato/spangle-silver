<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

// This Class For Response Result To return Success or error based on status comes from api
class ResponsHelper
{

    // This Function To return Success result
    public static function success($data = "Successfully", $message = 'Success', int $code = 200): JsonResponse
    {
        try {
            return response()->json([
                'status' => $code,
                'message' => $message,
                'data' => $data
            ], $code);
        } catch (\Exception $exception) {
            return ResponsHelper::error($exception,'Error -> ' . $exception->getMessage(),400);
        }
    }

    // This Function To return Error result
    public static function error($data, $message = 'Error', int $code = 400): JsonResponse
    {
        try {
            return response()->json([
                'status' => $code,
                'message' => $message,
                'data' => $data
            ], $code);
        } catch (\Exception $exception) {
            return ResponsHelper::error($exception,'Error -> ' . $exception->getMessage(),400);
        }
    }

    /**
     * Check if a value is a valid date (any format)
     *
     * @param mixed $value
     * @return bool
     */
    public static function isValidDate($value)
    {
        // strtotime يحول أي string مقبول لتاريخ إلى timestamp
        return strtotime($value) !== false;
    }
}
