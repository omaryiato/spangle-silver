<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
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
        return ResponseHelper::success($products_list, "Product list returned Successfully.", 200);
    }

    public function show(int $id)
    {
        $products_details = $this->productService->getProductDetails($id);
        return ResponseHelper::success($products_details, "Product Details returned Successfully.", 200);
    }

    public function store(Request $request)
    {
        try{
            $product_details = $this->productService->addNewProduct($request->all());
            return ResponseHelper::success($product_details,"Product Added Successfully", 201);
        } catch(Exception $exception){
            return ResponseHelper::error($exception->getMessage(), "There's Somthing Wrong.", 400);
        }
    }


    public function update(Request $request, int $id)
    {
        try{
            $product_details = $this->productService->updateProduct($request->all(), $id);
            return ResponseHelper::success($product_details,"Product Updated Successfully.", 201);
        } catch(Exception $exception){
            return ResponseHelper::error($exception->getMessage(), "There's Somthing Wrong.", 400);
        }
    }

    public function destroy(int $id)
    {
        try{
            $this->productService->deleteProduct($id);
            return ResponseHelper::success(null,"Product Deleted Successfully.", 200);
        } catch(Exception $exception){
            return ResponseHelper::error($exception->getMessage(), "There's Somthing Wrong.", 400);
        }
    }
}
