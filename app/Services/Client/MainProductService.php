<?php

namespace App\Services\Client;

use App\Helpers\ContactMessageHelper;
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
        return $this->mainProductRepository->reviewProduct($review_request);

    }

}
