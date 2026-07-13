<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\Dashboard\ProductService;
use Illuminate\Http\Request;
use Exception;

class ProductController extends Controller
{
    protected $productService;
    public function __construct(ProductService $productService) {
        $this->productService = $productService;
    }

    public function index()
    {
        $products_list = $this->productService->getProductsList();
        return ResponseHelper::success(
            ProductResource::collection($products_list),
            [
                'en' => trans('validation.data_retrieved'),
                'ar' => trans('validation.data_retrieved'),
            ],
            200);
    }

    public function show(Product $product)
    {
        $product_details = $this->productService->getProductDetails($product->id);
        return ResponseHelper::success(
            new ProductResource($product_details),
            [
                'en' => trans('validation.data_retrieved'),
                'ar' => trans('validation.data_retrieved'),
            ],
            200);
    }

    public function store(Request $request)
    {
        try{
            $product_details = $this->productService->addNewProduct($request->all());
            return ResponseHelper::success(
                new ProductResource($product_details),
                [
                    'en' => trans('validation.data_added'),
                    'ar' => trans('validation.data_added'),
                ],
                201);
        } catch(Exception $exception){
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error'),
                    'ar' => trans('validation.exception_error'),
                ],
                $exception->getMessage(),
                500);
        }
    }


    public function update(Request $request, Product $product)
    {
        try{
            $product_details = $this->productService->updateProduct($request->all(), $product->id);

            if (!$product_details) {
                return ResponseHelper::error(
                    $product_details,
                    [
                        'en' => trans('validation.data_not_found'),
                        'ar' => trans('validation.data_not_found'),
                    ],
                    404);
            }

            return ResponseHelper::success(
                $product_details,
                [
                    'en' => trans('validation.data_updated'),
                    'ar' => trans('validation.data_updated'),
                ],
                201);
        } catch(Exception $exception){
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error'),
                    'ar' => trans('validation.exception_error'),
                ],
                $exception->getMessage(),
                500);
        }
    }

    public function destroy(Product $product)
    {
        try{
            $product_details = $this->productService->deleteProduct($product->id);

            if (!$product_details) {
                return ResponseHelper::error(
                    $product_details,
                    [
                        'en' => trans('validation.data_not_found'),
                        'ar' => trans('validation.data_not_found'),
                    ],
                    404);
            }

            return ResponseHelper::success(
                null,
                [
                    'en' => trans('validation.data_deleted'),
                    'ar' => trans('validation.data_deleted'),
                ],
                200);
        } catch(Exception $exception){
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error'),
                    'ar' => trans('validation.exception_error'),
                ],
                $exception->getMessage(),
                500);
        }
    }
}
