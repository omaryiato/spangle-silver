<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    public static function success($data = null, $message = null, int $code = 200): JsonResponse
    {
        return response()->json(['status' => $code, 'success' => true,
            'message' => self::message($message, ['ar' => 'تمت العملية بنجاح', 'en' => 'Operation completed successfully']), 'data' => $data], $code);
    }

    public static function error($message = null, $errors = null, int $code = 500, string $errorCode = 'REQUEST_ERROR'): JsonResponse
    {
        if (! is_array($message) || ! isset($message['ar'], $message['en'])) {
            if (is_array($errors) && isset($errors['ar'], $errors['en'])) {
                $message = $errors;
                $errors = null;
            }
        }
        if ($code >= 500) {
            $errors = null;
            $message = ['ar' => 'حدث خطأ داخلي', 'en' => 'An internal error occurred'];
        }

        return response()->json(['status' => $code, 'success' => false,
            'message' => self::message($message, ['ar' => 'تعذر إتمام العملية', 'en' => 'Unable to complete the operation']),
            'error' => (object) ['code' => $errorCode, 'details' => (object) (is_array($errors) ? $errors : [])]], $code);
    }

    private static function message($message, array $fallback): array
    {
        return is_array($message) && isset($message['ar'], $message['en']) ? ['ar' => $message['ar'], 'en' => $message['en']] : $fallback;
    }

    public static function isValidDate($value): bool
    {
        return is_string($value) && strtotime($value) !== false;
    }
}
