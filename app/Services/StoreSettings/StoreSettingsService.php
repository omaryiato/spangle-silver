<?php

declare(strict_types=1);

namespace App\Services\StoreSettings;

use App\Models\StoreSetting;
use App\Repositories\StoreSettings\StoreSettingsRepository;
use App\Services\ActingUserService;
use App\Services\LoggingService;
use App\Services\MediaStorageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Throwable;

class StoreSettingsService
{
    public function __construct(
        private StoreSettingsRepository $repository,
        private ActingUserService $actor,
        private MediaStorageService $media,
        private LoggingService $logging,
    ) {}

    public function getSettings(): StoreSetting
    {
        return $this->repository->getSettings();
    }

    public function create(array $data): StoreSetting
    {
        $actorId = $this->actor->id();
        if ($this->repository->exists()) {
            throw new ConflictHttpException('Store settings already exist.');
        }
        $newPaths = [];
        try {
            $attributes = $this->prepareAttributes($data, $newPaths);
            $attributes['social_links'] = $this->mergeSocialLinks([], $data['social_links'] ?? []);
            // The database singleton index is authoritative for concurrent creates.
            $settings = $this->repository->create($this->actor->attributes($attributes));
        } catch (Throwable $exception) {
            $this->deleteFiles($newPaths);
            throw $exception;
        }
        $this->logging->record('CreateStoreSettings', $actorId, 'SUCCESS');

        return $settings;
    }

    public function update(StoreSetting $settings, array $data): StoreSetting
    {
        $actorId = $this->actor->id();
        $newPaths = [];
        $obsoletePaths = [];
        try {
            $attributes = $this->prepareAttributes($data, $newPaths);
            // Lock the read-modify-write cycle to preserve concurrent social-link edits and replacements.
            $settings = DB::transaction(function () use ($settings, $attributes, $data, &$obsoletePaths): StoreSetting {
                $current = $this->repository->findForUpdate((int) $settings->getKey());
                if (array_key_exists('social_links', $data)) {
                    $attributes['social_links'] = $this->mergeSocialLinks((array) $current->social_links, $data['social_links']);
                }
                foreach (['logo_path', 'favicon_path'] as $column) {
                    if (isset($attributes[$column]) && $current->{$column}) {
                        $obsoletePaths[] = $current->{$column};
                    }
                }

                return $this->repository->update($current, $this->actor->attributes($attributes, true));
            });
        } catch (Throwable $exception) {
            $this->deleteFiles($newPaths);
            throw $exception;
        }
        $this->deleteFiles($obsoletePaths);
        $this->logging->record('UpdateStoreSettings', $actorId, 'SUCCESS');

        return $settings;
    }

    public function delete(StoreSetting $settings): void
    {
        $actorId = $this->actor->id();
        // Use the same row lock as updates so cleanup targets the latest persisted files.
        $paths = DB::transaction(function () use ($settings): array {
            $current = $this->repository->findForUpdate((int) $settings->getKey());
            $paths = [$current->logo_path, $current->favicon_path];
            $this->repository->delete($current);

            return $paths;
        });
        $this->deleteFiles($paths);
        $this->logging->record('DeleteStoreSettings', $actorId, 'SUCCESS');
    }

    /** Return only provided scalar fields; clients cannot supply persisted file paths or audit values. */
    private function prepareAttributes(array $data, array &$newPaths): array
    {
        $attributes = array_intersect_key($data, array_flip([
            'name_ar', 'name_en', 'description_ar', 'description_en',
            'email_address', 'phone_number', 'whatsapp_number', 'currency_code',
        ]));
        foreach (['logo' => 'logo_path', 'favicon' => 'favicon_path'] as $field => $column) {
            if (($data[$field] ?? null) instanceof UploadedFile) {
                $path = $this->media->store($data[$field], 'store_settings/'.$field, $field === 'logo');
                $newPaths[] = $path;
                $attributes[$column] = $path;
            }
        }

        return $attributes;
    }

    /** Null removes one platform; omitted platforms remain unchanged, and empty links persist as a JSON object. */
    private function mergeSocialLinks(array $current, array $incoming): object
    {
        foreach ($incoming as $platform => $url) {
            if ($url === null) {
                unset($current[$platform]);
            } else {
                $current[$platform] = $url;
            }
        }

        return (object) $current;
    }

    private function deleteFiles(array $paths): void
    {
        foreach (array_unique(array_filter($paths)) as $path) {
            $this->media->delete($path);
        }
    }
}
