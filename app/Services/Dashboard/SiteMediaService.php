<?php

namespace App\Services\Dashboard;

use App\Helpers\ResponseHelper;
use App\Repositories\Dashboard\SiteMediaRepository;
use App\Services\ActingUserService;
use App\Services\MediaStorageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SiteMediaService
{
    protected $siteMediaRepository;

    public function __construct(SiteMediaRepository $siteMediaRepository, protected ActingUserService $actingUserService, protected MediaStorageService $mediaStorage)
    {
        $this->siteMediaRepository = $siteMediaRepository;
    }

    // getSiteMediaList Funtion To Get Shipping Methods List
    public function getSiteMediaList()
    {
        return $this->siteMediaRepository->getSiteMediaList();
    }

    // getSiteMediaDetails Funtion To Get Shipping Method Details
    public function getSiteMediaDetails(object $siteMedia)
    {
        return $this->siteMediaRepository->getSiteMediaDetails($siteMedia);
    }

    // addNewSiteMedia Funtion To Add new Shipping Method
    public function addNewSiteMedia($request)
    {
        $data = $request->validated();
        $media = $this->prepareMedia($request->file('media'));
        unset($data['media']);
        try {
            return $this->siteMediaRepository->addNewSiteMedia($this->actingUserService->attributes(array_merge($data, $media)));
        } catch (\Throwable $exception) {
            $this->mediaStorage->delete($media['file_path']);
            throw $exception;
        }
    }

    // updateSiteMedia Funtion To Update Shipping Method info
    public function updateSiteMedia($request, object $siteMedia)
    {
        $data = $request->validated();
        unset($data['media']);
        if (isset($data['alt_text'])) {
            $data['alt_text'] = array_replace($siteMedia->alt_text ?? [], $data['alt_text']);
        }
        $oldPath = $siteMedia->file_path;
        $media = null;
        if ($request->hasFile('media')) {
            $media = $this->prepareMedia($request->file('media'));
            $data = array_merge($data, $media);
        }
        try {
            $result = $this->siteMediaRepository->updateSiteMedia($siteMedia, $this->actingUserService->attributes($data, true));
        } catch (\Throwable $exception) {
            $this->mediaStorage->delete($media['file_path'] ?? null);
            throw $exception;
        }
        if ($media) {
            $this->mediaStorage->delete($oldPath);
        }

        return $result;
    }

    public function prepareMedia(UploadedFile $file, string $folder = 'website_media'): array
    {
        $mime = $file->getMimeType();
        $type = str_starts_with($mime, 'image/') ? 'image' : 'video';
        $path = $this->mediaStorage->store($file, 'website_media', $type === 'image');

        return ['file_name' => basename($path), 'original_name' => basename($file->getClientOriginalName()),
            'file_path' => $path, 'file_type' => $type, 'mime_type' => $type === 'image' ? 'image/webp' : $mime, 'file_size' => Storage::disk('documents')->size(substr($path, 10))];
    }

    protected function detectFileType(UploadedFile $file): string
    {
        $mime = $file->getMimeType();

        if (str_contains($mime, 'image')) {
            return 'image';
        }

        if (str_contains($mime, 'video')) {
            return 'video';
        }

        if (
            str_contains($mime, 'pdf')
            || str_contains($mime, 'word')
            || str_contains($mime, 'sheet')
        ) {
            return 'document';
        }

        return 'file';
    }

    public function deleteSiteMedia(object $siteMedia)
    {
        $path = $siteMedia->file_path;
        $result = $this->siteMediaRepository->deleteSiteMedia($siteMedia);
        $this->mediaStorage->delete($path);

        return $result;
    }

    public function stream(object $siteMedia)
    {
        // $media_details = $this->siteMediaRepository->findById($id);

        // if(!$media_details){
        //     return false;
        // }

        if (! str_starts_with($siteMedia->mime_type, 'video/')) {
            return ResponseHelper::error(
                $siteMedia,
                [
                    'en' => 'This file is not a video',
                    'ar' => 'This file is not a video',
                ],
                400);
        }

        $relativePath = ltrim($siteMedia?->file_path ?? '', '/');
        $path = public_path($relativePath);

        if (! file_exists($path)) {
            return ResponseHelper::error(
                null,
                [
                    'en' => 'Media file not found',
                    'ar' => 'Media file not found',
                ],
                400);
        }

        $size = filesize($path);
        $mimeType = $siteMedia->mime_type;

        if (request()->hasHeader('Range')) {
            return $this->handleRangeRequest($path, $size, $mimeType);
        }

        return response()->stream(function () use ($path) {
            $stream = fopen($path, 'rb');
            while (! feof($stream)) {
                echo fread($stream, 65536);
                flush();
            }
            fclose($stream);
        }, 200, [
            'Content-Type' => $mimeType,
            'Content-Length' => $size,
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'no-cache',
        ]);
    }

    private function handleRangeRequest(string $path, int $size, string $mimeType)
    {
        preg_match('/bytes=(\d+)-(\d*)/', request()->header('Range'), $matches);

        $start = (int) $matches[1];
        $end = isset($matches[2]) && $matches[2] !== ''
                    ? (int) $matches[2]
                    : $size - 1;

        $chunkSize = $end - $start + 1;

        return response()->stream(function () use ($path, $start, $chunkSize) {
            $stream = fopen($path, 'rb');
            fseek($stream, $start);
            $remaining = $chunkSize;

            while (! feof($stream) && $remaining > 0) {
                $toRead = min(65536, $remaining);
                echo fread($stream, $toRead);
                $remaining -= $toRead;
                flush();
            }

            fclose($stream);
        }, 206, [
            'Content-Type' => $mimeType,
            'Content-Range' => "bytes $start-$end/$size",
            'Content-Length' => $chunkSize,
            'Accept-Ranges' => 'bytes',
        ]);
    }
}
