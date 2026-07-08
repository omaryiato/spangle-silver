<?php

namespace App\Repositories\Client;

use App\Models\Category;
use App\Models\SiteImage;
use App\Models\SiteTheme;

class MainRepository
{
    public function getAllActiveData()
    {
        return Category::where('status', 1)
                ->whereHas('products', function ($query) {
                    $query->where('product_status', 1);
                })
                ->with([
                    'products' => function ($query) {
                        $query->where('product_status', 1);
                    },
                    'products.images',
                    'products.variants' => fn($q) => $q->where('status', 1),
                    'products.variants.color',
                    'products.variants.size',
                    'products.reviews',
                    'products.reviews.user' => fn($q) => $q->where('status', 1),
                    'products.material',
                    'products.stone',
                ])
                ->get();
    }


    public function getSiteTheme()
    {
        return SiteTheme::where('status', 1)->get();
    }

    public function getSiteMedia()
    {
        return SiteImage::where('status', 1)->get();
    }

}
