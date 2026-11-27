<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Throwable;

class LoggingService
{
    /** Logging is a best-effort side effect and never interrupts a business operation. */
    public function record(string $operation, int $userId, string $status): void
    {
        try {
            Log::build(['driver' => 'single', 'path' => storage_path('logs/'.date('Y-m-d').'/'.$operation.'.log')])
                ->info($operation, ['user_id' => $userId, 'status' => $status]);
        } catch (Throwable) {
            try {
                Log::warning('Business logging unavailable', ['operation' => $operation]);
            } catch (Throwable) {
            }
        }
    }
}
