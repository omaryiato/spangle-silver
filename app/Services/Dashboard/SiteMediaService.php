<?php

namespace App\Services\Dashboard;

use App\Helpers\ResponseHelper;
use App\Repositories\Dashboard\SiteMediaRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\Format;
use Intervention\Image\Laravel\Facades\Image;


class SiteMediaService
{

    protected $siteMediaRepository;

    public function __construct(SiteMediaRepository $siteMediaRepository)
    {
        $this->siteMediaRepository = $siteMediaRepository;
    }

    // getSiteMediaList Funtion To Get Shipping Methods List
    public function getSiteMediaList()
    {
        return  $this->siteMediaRepository->getSiteMediaList();
    }

    // getSiteMediaDetails Funtion To Get Shipping Method Details
    public function getSiteMediaDetails(object $siteMedia)
    {
        return $this->siteMediaRepository->getSiteMediaDetails($siteMedia);
    }

    // addNewSiteMedia Funtion To Add new Shipping Method
    public function addNewSiteMedia($request)
    {
        $site_media_request = $request->validated();

        if ($request->hasFile('image')) {
            $site_image = $this->uploadSiteMedia(
                $request->file('image'),
                $site_media_request['type']
            );

            $site_media_request['image'] = $site_image;
        }
        return $this->siteMediaRepository->addNewSiteMedia($this->prepareRequestInfo($site_media_request));
    }

    // updateSiteMedia Funtion To Update Shipping Method info
    public function updateSiteMedia($request, object $siteMedia)
    {
        $site_media_request = $request->validated();

        // $site_media_details = $this->siteMediaRepository->getSiteMediaDetails($siteMedia);

        // if(!$site_media_details){
        //     return null;
        // }

        if ($request->hasFile('image')) {

            // Delete old image
            if ($siteMedia->image) {

                $old_path = public_path($siteMedia->image);

                if (File::exists($old_path)) {
                    File::delete($old_path);
                }
            }


            // Upload new Media
            $site_media_request['image'] = $this->uploadSiteMedia(
                $request->file('image'),
                $site_media_request['type']
            );

        } else {
            // keep old Media
            $site_media_request['image'] = $siteMedia->image;
        }
        return $this->siteMediaRepository->updateSiteMedia($siteMedia, $this->prepareRequestInfo($site_media_request));
    }

    // deleteSiteMedia Funtion To Delete Shipping Method
    public function deleteSiteMedia(object $siteMedia)
    {

        // $site_media_details = $this->siteMediaRepository->getSiteMediaDetails($siteMedia);
        // if(!$site_media_details){
        //     return null;
        // }
        return $this->siteMediaRepository->deleteSiteMedia($siteMedia);
    }

    public function uploadSiteMedia(UploadedFile $file, string $type)
    {
        $extension = $file->getClientOriginalExtension();

        $file_name = str_replace(' ', '_', $type) . '_' . date('dmYHis') . '.' . $extension;

        $folder_path = public_path("documents/site_media/{$type}/");


        if (!File::exists($folder_path)) {
            File::makeDirectory($folder_path, 0755, true);
        }

        $webp_name = pathinfo($file_name, PATHINFO_FILENAME) . '.webp';

        $image = Image::decode($file);

        // encode to webp
        $encoded = $image->encodeUsingFormat(
            Format::WEBP,
            quality: 85
        );

        // save encoded image
        $encoded->save("{$folder_path}/{$webp_name}");


        $file->move($folder_path, $file_name);


        return "documents/site_media/{$type}/{$webp_name}";
    }

    public function prepareRequestInfo(array $request_info)
    {
        $request_data = [
            'type' => $request_info['type'] ?? null,
            'status' => $request_info['status'] ?? null,
        ];


        if (isset($request_info['created_by'])) {
            $request_data['created_by'] = $request_info['created_by'];
        }


        if (isset($request_info['updated_by'])) {
            $request_data['updated_by'] = $request_info['updated_by'];
        }


        return $request_data;
    }


    public function prepareMedia(UploadedFile $file, string $folder = 'website_media')
    {
        $media_name = $this->generateMediaName($file);

        $original_name = $file->getClientOriginalName();
        $mime_type     = $file->getMimeType();
        $file_size     = $file->getSize();
        // $file_type     = $this->detectFileType($file);

        // $media_path = $this->uploadMediaFile($file, $media_name, $folder);

        $file_type = $this->detectFileType($file);

        if ($file_type === 'image') {

            $media_path = $this->uploadImage(
                $file,
                $media_name,
                $folder
            );

        } elseif ($file_type === 'video') {

            $media_path = $this->uploadVideo(
                $file,
                $media_name,
                $folder
            );

        } else {

            $media_path = $this->uploadFile(
                $file,
                $media_name,
                $folder
            );
        }

        // $media_details = $this->siteMediaRepository->addNewMedia([
        //     'file_name'     => $media_name,
        //     'original_name' => $original_name,
        //     'file_path'     => $media_path,
        //     'file_type'     => $file_type,
        //     'mime_type'     => $mime_type,
        //     'file_size'     => $file_size,
        // ]);

        return [
                'file_name'     => $media_name,
                'original_name' => $original_name,
                'file_path'     => $media_path,
                'file_type'     => $file_type,
                'mime_type'     => $mime_type,
                'file_size'     => $file_size,
            ];
    }

    protected function generateMediaName(UploadedFile $file): string
    {
        $original_name = pathinfo(
            $file->getClientOriginalName(),
            PATHINFO_FILENAME
        );

        $original_name = str_replace(' ', '_', $original_name);

        $date = now()->format('d-M-Y');

        $extension = $file->getClientOriginalExtension();

        return "{$original_name}_{$date}.{$extension}";
    }

    protected function uploadFile(UploadedFile $file,string $media_name,string $folder): string
    {
        $destination_path = public_path("documents/website_media/{$folder}");

        if (!File::exists($destination_path)) {
            File::makeDirectory($destination_path, 0755, true);
        }

        $file->move($destination_path, $media_name);

        return "documents/website_media/{$folder}/{$media_name}";
    }

    protected function uploadImage(UploadedFile $file,string $media_name,string $folder): string
    {
        $destination_path = public_path("documents/website_media/{$folder}");

        if (!File::exists($destination_path)) {
            File::makeDirectory($destination_path, 0755, true);
        }

        $webp_name = pathinfo($media_name, PATHINFO_FILENAME) . '.webp';

        $image = Image::decode($file);

        // encode to webp
        $encoded = $image->encodeUsingFormat(
            Format::WEBP,
            quality: 85
        );

        // save encoded image
        $encoded->save("{$destination_path}/{$webp_name}");

        return "documents/website_media/{$folder}/{$webp_name}";
    }

    protected function uploadVideo(UploadedFile $file, string $media_name, string $folder): string
    {

        $destination_path = public_path("documents/website_media/{$folder}");

        if (!File::exists($destination_path)) {
            File::makeDirectory(
                $destination_path,
                0755,
                true
            );
        }

        // $video_name = pathinfo(
        //     $media_name,
        //     PATHINFO_FILENAME
        // ) . '.mp4';

        $file->move($destination_path,$media_name);

        return "documents/website_media/{$folder}/{$media_name}";
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

    public function deleteMedia($media)
    {
        if (!$media) {
            return false;
        }

        if (
            $media->file_path &&
            File::exists(public_path($media->file_path))
        ) {
            File::delete(public_path($media->file_path));
        }

        return $this->siteMediaRepository->deleteMedia($media);
    }

    public function stream(int $id)
    {
        $media_details = $this->siteMediaRepository->findById($id);

        if(!$media_details){
            return false;
        }

        if (!str_starts_with($media_details->mime_type, 'video/')) {
            return ResponseHelper::error(
                $media_details,
                [
                    'en' => "This file is not a video",
                    'ar' => "This file is not a video",
                ],
                400);
        }

        $relativePath = ltrim('api/'.$media_details?->file_path, '/');
        $path = public_path($relativePath);

        if (!file_exists($path)) {
            return ResponseHelper::error(
                $path,
                [
                    'en' => "File not found on disk: $path",
                    'ar' => "File not found on disk: $path",
                ],
                400);
        }

        $size     = filesize($path);
        $mimeType = $media_details->mime_type;

        if (request()->hasHeader('Range')) {
            return $this->handleRangeRequest($path, $size, $mimeType);
        }

        return response()->stream(function () use ($path) {
            $stream = fopen($path, 'rb');
            while (!feof($stream)) {
                echo fread($stream, 65536);
                flush();
            }
            fclose($stream);
        }, 200, [
            'Content-Type'   => $mimeType,
            'Content-Length' => $size,
            'Accept-Ranges'  => 'bytes',
            'Cache-Control'  => 'no-cache',
        ]);
    }

    private function handleRangeRequest(string $path, int $size, string $mimeType)
    {
        preg_match('/bytes=(\d+)-(\d*)/', request()->header('Range'), $matches);

        $start = (int) $matches[1];
        $end   = isset($matches[2]) && $matches[2] !== ''
                    ? (int) $matches[2]
                    : $size - 1;

        $chunkSize = $end - $start + 1;

        return response()->stream(function () use ($path, $start, $chunkSize) {
            $stream = fopen($path, 'rb');
            fseek($stream, $start);
            $remaining = $chunkSize;

            while (!feof($stream) && $remaining > 0) {
                $toRead = min(65536, $remaining);
                echo fread($stream, $toRead);
                $remaining -= $toRead;
                flush();
            }

            fclose($stream);
        }, 206, [
            'Content-Type'   => $mimeType,
            'Content-Range'  => "bytes $start-$end/$size",
            'Content-Length' => $chunkSize,
            'Accept-Ranges'  => 'bytes',
        ]);
    }

}

