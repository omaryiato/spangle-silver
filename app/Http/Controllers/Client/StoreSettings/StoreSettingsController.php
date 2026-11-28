<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client\StoreSettings;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\StoreSettings\StoreSettingsResource;
use App\Services\StoreSettings\StoreSettingsService;
use Illuminate\Http\JsonResponse;

class StoreSettingsController extends Controller
{
    public function __construct(private StoreSettingsService $service) {}

    public function index(): JsonResponse
    {
        return ResponseHelper::success(StoreSettingsResource::make($this->service->getSettings(), false));
    }
}
