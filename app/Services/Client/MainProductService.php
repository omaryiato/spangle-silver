<?php

namespace App\Services\Client;

use App\Helpers\ContactMessageHelper;
use App\Helpers\ResponseHelper;
use App\Repositories\Client\MainProductRepository;

class MainProductService
{
    public function __construct(
        protected MainProductRepository $mainProductRepository,
        protected ContactMessageHelper $contactMessageHelper
    ) {}

    public function getProductsList(int $category_id)
    {
        return $this->mainProductRepository->getProductsList($category_id);
    }

    public function getProductDetails(int $product_id)
    {
        return $this->mainProductRepository->getProductDetails($product_id);
    }

    public function getShippingMethodsList()
    {
        return $this->mainProductRepository->getShippingMethodsList();
    }

    public function getCouponsList()
    {
        return $this->mainProductRepository->getCouponsList();
    }


    public function reviewProduct($review_request)
    {
        return $this->mainProductRepository->reviewProduct($this->prepareReviewRequest($review_request));

    }

    public function prepareReviewRequest(array $review_request)
    {
        $request_data = [
            'user_id' => $review_request['user_id'] ?? null,
            'product_id' => $review_request['product_id'] ?? null,
            'comment' => $review_request['comment'] ?? null,
            'rating' => $review_request['rating'] ?? 1,
        ];

        return $request_data;
    }

    public function stream(object $product)
    {

        $relativePath = ltrim('api/'.$product?->product_reels, '/');

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
        $mimeType = 'video/mp4';

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
