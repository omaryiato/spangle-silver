<?php

declare(strict_types=1);
use App\Repositories\Dashboard\CategoryRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\Support\DatabaseFixture as Fixture;

beforeEach(function (): void {
    Fixture::create();
    $this->admin = Fixture::user(1);
    Sanctum::actingAs($this->admin);
    $this->catalog = Fixture::catalog();
});
test('category partial update preserves omitted values and original creator', function (): void {
    $this->postJson('/api/dashboard/category/'.$this->catalog['category'], ['category_en_name' => 'New name', 'created_by' => 123, 'updated_by' => 456])->assertOk();
    $row = DB::table('category')->first();
    expect($row->category_description)->toBe('Preserved description')->and($row->category_image)->toBe('documents/categories/old.webp')->and((int) $row->status)->toBe(0)->and((int) $row->created_by)->toBe(900)->and((int) $row->updated_by)->toBe($this->admin->id);
});
test('explicit null clears nullable text and zero remains a valid update', function (): void {
    $this->postJson('/api/dashboard/category/'.$this->catalog['category'], ['category_description' => null, 'status' => 0])->assertOk();
    expect(DB::table('category')->value('category_description'))->toBeNull();
    expect((int) DB::table('category')->value('status'))->toBe(0);
});
test('user partial update never resets omitted or empty password', function (): void {
    $user = Fixture::user();
    $hash = $user->password;
    $this->postJson('/api/dashboard/user/'.$user->id, ['full_name' => 'Changed'])->assertOk();
    expect($user->fresh()->password)->toBe($hash)->and($user->fresh()->email_address)->toBe($user->email_address);
    $this->postJson('/api/dashboard/user/'.$user->id, ['password' => ''])->assertOk();
    expect($user->fresh()->password)->toBe($hash);
    $this->postJson('/api/dashboard/user/'.$user->id, ['password' => 'Updated-password-123'])->assertOk();
    expect(Hash::check('Updated-password-123', $user->fresh()->password))->toBeTrue();
});
test('product updates retain reel images and stable variant IDs', function (): void {
    DB::table('product_images')->insert(['product_id' => $this->catalog['product'], 'image' => 'documents/products/image.webp']);
    $this->postJson('/api/dashboard/product/'.$this->catalog['product'], ['product_en_description' => 'Changed'])->assertOk();
    expect(DB::table('products')->value('product_reels'))->toBe('documents/products/old.mp4');
    expect(DB::table('product_images')->count())->toBe(1);
    $this->postJson('/api/dashboard/product/'.$this->catalog['product'], ['product_variants' => [['id' => $this->catalog['variant'], 'stock' => 4]]])->assertOk();
    expect(DB::table('product_variants')->count())->toBe(1)->and((int) DB::table('product_variants')->value('id'))->toBe($this->catalog['variant'])->and(DB::table('product_variants')->value('sku'))->toBe('RING-1');
});
test('media metadata update keeps existing file and record identity', function (): void {
    $id = DB::table('site_media')->insertGetId(['file_path' => 'documents/old.mp4', 'type' => 'slider', 'status' => 1, 'alt_text' => json_encode(['ar' => 'old AR', 'en' => 'old']), 'created_by' => 900]);
    $this->postJson('/api/dashboard/site-media/'.$id, ['alt_text' => ['en' => 'New caption']])->assertOk();
    $row = DB::table('site_media')->first();
    expect((int) $row->id)->toBe($id)->and($row->file_path)->toBe('documents/old.mp4')->and((int) $row->created_by)->toBe(900)->and(json_decode($row->alt_text, true))->toBe(['ar' => 'old AR', 'en' => 'New caption']);
});
test('unique validation cannot be bypassed using submitted id', function (): void {
    $user = Fixture::user();
    $this->postJson('/api/dashboard/user/'.$user->id, ['email_address' => $this->admin->email_address, 'id' => 123456])->assertUnprocessable();
});

test('remaining dashboard updates preserve unrelated columns', function (string $table, string $route, array $initial, array $change, string $preserved): void {
    $id = DB::table($table)->insertGetId($initial);
    $this->postJson('/api/dashboard/'.$route.'/'.$id, $change)->assertOk();
    $row = DB::table($table)->where('id', $id)->first();
    expect((string) $row->{$preserved})->toBe((string) $initial[$preserved]);
})->with([
    'address' => ['addresses', 'address', ['user_id' => 1, 'full_name' => 'Retained', 'address_line' => 'Road', 'city' => 'Amman', 'country' => 'Jordan', 'is_default' => 1], ['label' => 'Office'], 'full_name'],
    'shipping' => ['shipping_methods', 'shipping-method', ['method_en_name' => 'Delivery', 'method_ar_name' => 'Arabic name', 'price' => 5, 'status' => 1], ['estimated_days' => 3], 'price'],
    'lookup type' => ['lookup_types', 'lookup-type', ['type_en_name' => 'Colors', 'type_ar_name' => 'Colors AR', 'type_description' => 'Retained', 'status' => 1], ['type_en_name' => 'Colors'], 'type_description'],
    'lookup value' => ['lookup_values', 'lookup-value', ['type_id' => 1, 'code' => 'RED', 'en_meaning' => 'Red', 'ar_meaning' => 'Red AR', 'description' => 'Retained', 'status' => 1], ['description' => 'New description'], 'code'],
    'coupon' => ['coupons', 'coupon', ['code' => 'SAVE', 'discount_amount' => 10, 'minimum_order_amount' => 50, 'max_usage' => 20, 'used_count' => 3, 'status' => 1], ['code' => 'SAVE'], 'used_count'],
    'theme' => ['site_themes', 'site-theme', ['theme_name' => 'Silver', 'background_image' => 'documents/old.webp', 'color_scheme' => 'white', 'status' => 1], ['font_style' => 'Arial'], 'background_image'],
]);
test('invalid uploaded replacement keeps the original file reference', function (): void {
    $file = UploadedFile::fake()->create('malicious.php', 1, 'application/x-httpd-php');
    $this->post('/api/dashboard/category/'.$this->catalog['category'], ['category_image' => $file], ['Accept' => 'application/json'])->assertUnprocessable();
    expect(DB::table('category')->value('category_image'))->toBe('documents/categories/old.webp');
});
test('validated image replacement saves new file before deleting the old one', function (): void {
    Storage::fake('documents');
    Storage::disk('documents')->put('categories/old.webp', 'old image');
    $file = UploadedFile::fake()->image('replacement.png', 10, 10);
    $this->post('/api/dashboard/category/'.$this->catalog['category'], ['category_image' => $file], ['Accept' => 'application/json'])->assertOk();
    $path = DB::table('category')->value('category_image');
    expect($path)->not->toBe('documents/categories/old.webp');
    Storage::disk('documents')->assertExists(substr($path, 10));
    Storage::disk('documents')->assertMissing('categories/old.webp');
});
test('user deletion deactivates without deleting reviews and revokes tokens', function (): void {
    $user = Fixture::user();
    DB::table('product_review')->insert(['user_id' => $user->id, 'product_id' => $this->catalog['product'], 'rating' => 5]);
    $user->createToken('test');
    $this->deleteJson('/api/dashboard/user/'.$user->id)->assertSuccessful();
    expect($user->fresh())->not->toBeNull()->and($user->fresh()->status)->toBe(0);
    expect(DB::table('product_review')->count())->toBe(1)->and(DB::table('personal_access_tokens')->count())->toBe(0);
});

test('a variant from another product cannot be reassigned through an update', function (): void {
    $other = DB::table('products')->insertGetId(['product_en_name' => 'Other', 'product_ar_name' => 'Other AR', 'category_id' => $this->catalog['category']]);
    $variant = DB::table('product_variants')->insertGetId(['product_id' => $other, 'sku' => 'OTHER', 'stock' => 10]);
    $this->postJson('/api/dashboard/product/'.$this->catalog['product'], ['product_variants' => [['id' => $variant, 'stock' => 0, 'sku' => 'OTHER']]])->assertUnprocessable();
    expect((int) DB::table('product_variants')->where('id', $variant)->value('stock'))->toBe(10);
});
test('malformed variant IDs return validation errors instead of internal exceptions', function (): void {
    $this->postJson('/api/dashboard/product/'.$this->catalog['product'], ['product_variants' => [['id' => ['unexpected'], 'sku' => 'test']]])->assertUnprocessable();
});
test('a failed database save preserves the old image and removes the replacement', function (): void {
    Storage::fake('documents');
    Storage::disk('documents')->put('categories/old.webp', 'old');
    $repository = Mockery::mock(CategoryRepository::class);
    $repository->shouldReceive('updateCategoryDetails')->once()->andThrow(new RuntimeException('Internal database detail'));
    app()->instance(CategoryRepository::class, $repository);
    $response = $this->post('/api/dashboard/category/'.$this->catalog['category'], ['category_image' => UploadedFile::fake()->image('new.png')], ['Accept' => 'application/json'])->assertStatus(500);
    expect($response->getContent())->not->toContain('Internal database detail');
    expect(Storage::disk('documents')->allFiles())->toBe(['categories/old.webp']);
});
test('password changes revoke existing tokens', function (): void {
    $user = Fixture::user();
    $user->createToken('old');
    $this->postJson('/api/dashboard/user/'.$user->id, ['password' => 'Changed-password-123'])->assertOk();
    expect($user->tokens()->count())->toBe(0);
});
