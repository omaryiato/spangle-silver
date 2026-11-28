<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard\StoreSettings;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSettings\StoreStoreSettingsRequest;
use App\Http\Requests\StoreSettings\UpdateStoreSettingsRequest;
use App\Http\Resources\StoreSettings\StoreSettingsResource;
use App\Models\StoreSetting;
use App\Services\StoreSettings\StoreSettingsService;
use Illuminate\Http\JsonResponse;

class StoreSettingsController extends Controller
{
    public function __construct(private StoreSettingsService $service) {}

    public function index(): JsonResponse
    {
        return ResponseHelper::success(StoreSettingsResource::make($this->service->getSettings()));
    }

    public function store(StoreStoreSettingsRequest $request): JsonResponse
    {
        return ResponseHelper::success(StoreSettingsResource::make($this->service->create($request->validated())),
            ['ar' => 'تم إنشاء إعدادات المتجر', 'en' => 'Store settings created'], 201);
    }

    public function update(UpdateStoreSettingsRequest $request, StoreSetting $storeSetting): JsonResponse
    {
        return ResponseHelper::success(StoreSettingsResource::make($this->service->update($storeSetting, $request->validated())),
            ['ar' => 'تم تحديث إعدادات المتجر', 'en' => 'Store settings updated']);
    }

    public function destroy(StoreSetting $storeSetting): JsonResponse
    {
        $this->service->delete($storeSetting);

        return ResponseHelper::success(null, ['ar' => 'تم حذف إعدادات المتجر', 'en' => 'Store settings deleted']);
    }
}
