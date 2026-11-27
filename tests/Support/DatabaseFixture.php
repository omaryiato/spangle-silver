<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

final class DatabaseFixture
{
    /** Isolated SQLite fixtures only. No migrations or production schema/data writes. */
    public static function create(): void
    {
        if (DB::connection()->getDriverName() !== 'sqlite' || DB::connection()->getDatabaseName() !== ':memory:') {
            throw new \RuntimeException('Tests require an isolated in-memory SQLite connection.');
        }
        $tables = [
            'users' => ['full_name', 'user_name', 'phone_number', 'email_address', 'password', 'status', 'user_type'],
            'personal_access_tokens' => ['tokenable_type', 'tokenable_id', 'name', 'token', 'abilities', 'last_used_at', 'expires_at'],
            'category' => ['category_en_name', 'category_ar_name', 'category_description', 'category_image', 'status'],
            'products' => ['product_en_name', 'product_ar_name', 'product_en_description', 'product_ar_description', 'product_material', 'product_stone', 'product_reels', 'product_price', 'product_status', 'category_id'],
            'product_images' => ['product_id', 'image', 'is_primary', 'sort_order'],
            'product_variants' => ['product_id', 'color_id', 'size_id', 'sku', 'stock', 'price', 'status'],
            'product_review' => ['product_id', 'user_id', 'comment', 'rating'],
            'lookup_types' => ['type_en_name', 'type_ar_name', 'type_description', 'status'],
            'lookup_values' => ['type_id', 'code', 'en_meaning', 'ar_meaning', 'description', 'status', 'color'],
            'addresses' => ['user_id', 'label', 'full_name', 'address_line', 'city', 'country', 'postal_code', 'phone', 'is_default'],
            'shipping_methods' => ['method_en_name', 'method_ar_name', 'price', 'estimated_days', 'status'],
            'orders' => ['user_id', 'address_id', 'shipping_id', 'subtotal', 'shipping_cost', 'discount', 'total_price', 'status', 'notes', 'snap_user_name', 'snap_address', 'snap_city', 'snap_country', 'snap_phone', 'snap_email', 'snap_postal_code'],
            'orders_details' => ['order_id', 'variant_id', 'quantity', 'unit_price', 'total_price'],
            'coupon_usage' => ['coupon_id', 'user_id', 'order_id', 'used_at'],
            'coupons' => ['code', 'discount_amount', 'minimum_order_amount', 'max_usage', 'used_count', 'expires_at', 'status'],
            'cart_products' => ['user_id', 'variant_id', 'quantity'],
            'user_wishlist' => ['user_id', 'product_id'],
            'site_media' => ['file_name', 'original_name', 'file_path', 'file_type', 'mime_type', 'file_size', 'alt_text', 'type', 'status'],
            'site_themes' => ['theme_name', 'color_scheme', 'font_style', 'background_image', 'borders', 'status'],
            'payment_transactions' => ['order_id', 'status'],
        ];
        foreach ($tables as $name => $columns) {
            Schema::create($name, function (Blueprint $table) use ($columns, $name): void {
                $table->id();
                foreach ($columns as $column) {
                    $table->text($column)->nullable();
                }
                if ($name !== 'coupon_usage') {
                    $table->timestamps();
                }
                if (! in_array($name, ['coupon_usage', 'personal_access_tokens', 'cart_products', 'user_wishlist', 'shipping_methods', 'addresses'], true)) {
                    $table->unsignedBigInteger('created_by')->nullable();
                    $table->unsignedBigInteger('updated_by')->nullable();
                }
                $table->timestamp('deleted_at')->nullable();
                if ($name === 'users') {
                    $table->unique('email_address');
                    $table->unique('user_name');
                }
                if ($name === 'personal_access_tokens') {
                    $table->unique('token');
                }
                if ($name === 'product_variants') {
                    $table->unique('sku');
                }
                if ($name === 'cart_products') {
                    $table->unique(['user_id', 'variant_id']);
                }
                if ($name === 'user_wishlist') {
                    $table->unique(['user_id', 'product_id']);
                }
            });
        }
    }

    public static function user(int $type = 0): User
    {
        static $sequence = 0;
        $sequence++;

        return User::create(['full_name' => 'Test User', 'user_name' => 'user'.$sequence, 'email_address' => 'u'.$sequence.'@example.com',
            'phone_number' => '0500000000', 'password' => 'Test-password-123', 'status' => 1, 'user_type' => $type]);
    }

    public static function catalog(): array
    {
        $category = DB::table('category')->insertGetId(['category_en_name' => 'Silver', 'category_ar_name' => 'Silver AR', 'category_description' => 'Preserved description', 'category_image' => 'documents/categories/old.webp', 'status' => 0, 'created_by' => 900]);
        $product = DB::table('products')->insertGetId(['category_id' => $category, 'product_en_name' => 'Ring', 'product_ar_name' => 'Ring AR', 'product_en_description' => 'Preserved details', 'product_price' => 100, 'product_status' => 1, 'product_reels' => 'documents/products/old.mp4', 'created_by' => 900]);
        $variant = DB::table('product_variants')->insertGetId(['product_id' => $product, 'sku' => 'RING-1', 'stock' => 10, 'price' => 100, 'status' => 1, 'created_by' => 900]);
        $shipping = DB::table('shipping_methods')->insertGetId(['method_en_name' => 'Delivery', 'method_ar_name' => 'Delivery AR', 'price' => 5, 'status' => 1]);

        return compact('category', 'product', 'variant', 'shipping');
    }
}
