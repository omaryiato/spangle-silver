<?php

declare(strict_types=1);

namespace App\Repositories\StoreSettings;

use App\Models\StoreSetting;

class StoreSettingsRepository
{
    public function getSettings(): StoreSetting
    {
        return StoreSetting::query()->firstOrFail();
    }

    public function exists(): bool
    {
        return StoreSetting::query()->exists();
    }

    public function create(array $data): StoreSetting
    {
        return StoreSetting::query()->create($data);
    }

    public function findForUpdate(int $id): StoreSetting
    {
        return StoreSetting::query()->whereKey($id)->lockForUpdate()->firstOrFail();
    }

    public function update(StoreSetting $settings, array $data): StoreSetting
    {
        $settings->update($data);

        return $settings;
    }

    public function delete(StoreSetting $settings): void
    {
        $settings->delete();
    }
}
