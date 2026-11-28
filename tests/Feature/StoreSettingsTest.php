<?php

declare(strict_types=1);

use App\Models\StoreSetting;
use App\Repositories\StoreSettings\StoreSettingsRepository;
use App\Services\StoreSettings\StoreSettingsService;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\Support\DatabaseFixture;

beforeEach(function (): void {
    DatabaseFixture::create();
    Schema::create('store_settings', function (Blueprint $table): void {
        $table->id();
        $table->string('name_ar');
        $table->string('name_en');
        $table->text('description_ar')->nullable();
        $table->text('description_en')->nullable();
        $table->string('logo_path', 500)->nullable();
        $table->string('favicon_path', 500)->nullable();
        $table->string('email_address')->nullable();
        $table->string('phone_number', 30)->nullable();
        $table->string('whatsapp_number', 30)->nullable();
        $table->char('currency_code', 3);
        $table->json('social_links')->default('{}');
        $table->foreignId('created_by')->nullable()->constrained('users')->restrictOnDelete();
        $table->foreignId('updated_by')->nullable()->constrained('users')->restrictOnDelete();
        $table->timestampsTz();
    });
    // SQLite counterpart of the verified PostgreSQL unique singleton expression index.
    DB::statement('CREATE UNIQUE INDEX uq_store_settings_single_record ON store_settings ((1))');
    Storage::fake('documents');
    $this->admin = DatabaseFixture::user(1);
    $this->payload = ['name_ar' => 'متجر الفضة', 'name_en' => 'Silver Store', 'currency_code' => 'SAR'];
});

function createStoreSettings(array $overrides = []): StoreSetting
{
    return StoreSetting::create(array_replace([
        'name_ar' => 'متجر الفضة', 'name_en' => 'Silver Store', 'currency_code' => 'SAR',
        'description_en' => 'Original description', 'phone_number' => '+966500000000',
        'logo_path' => 'documents/store_settings/logo/old.webp',
        'favicon_path' => 'documents/store_settings/favicon/old.png',
        'social_links' => (object) ['instagram' => 'https://instagram.com/original', 'tiktok' => 'https://tiktok.com/@original'],
    ], $overrides));
}

test('public settings omit audit metadata even for an authenticated administrator', function (): void {
    createStoreSettings(['created_by' => $this->admin->id, 'updated_by' => $this->admin->id]);
    $this->getJson('/api/v1/client/store-settings')->assertOk()
        ->assertJsonPath('data.name_en', 'Silver Store')->assertJsonMissingPath('data.created_by')
        ->assertJsonMissingPath('data.updated_by')->assertJsonMissingPath('data.created_at')
        ->assertJsonMissingPath('data.updated_at')->assertJsonMissingPath('data.logo_path');
    Sanctum::actingAs($this->admin);
    $this->getJson('/api/v1/client/store-settings')->assertOk()->assertJsonMissingPath('data.created_by');
    $this->getJson('/api/v1/dashboard/store-settings')->assertOk()->assertJsonPath('data.created_by', $this->admin->id);
});

test('missing settings return a standardized not found response', function (): void {
    $this->getJson('/api/v1/client/store-settings')->assertNotFound()->assertJsonPath('error.code', 'NOT_FOUND');
    Sanctum::actingAs($this->admin);
    $this->getJson('/api/v1/dashboard/store-settings')->assertNotFound();
});

test('all management operations require an active administrator', function (): void {
    $settings = createStoreSettings();
    $requests = [
        ['get', '/api/v1/dashboard/store-settings', []],
        ['post', '/api/v1/dashboard/store-settings', $this->payload],
        ['patch', '/api/v1/dashboard/store-settings/'.$settings->id, ['name_en' => 'Changed']],
        ['delete', '/api/v1/dashboard/store-settings/'.$settings->id, []],
    ];
    foreach ($requests as [$method, $url, $data]) {
        $this->json($method, $url, $data)->assertUnauthorized();
    }
    Sanctum::actingAs(DatabaseFixture::user());
    foreach ($requests as [$method, $url, $data]) {
        $this->json($method, $url, $data)->assertForbidden();
    }
    $this->admin->update(['status' => 0]);
    Sanctum::actingAs($this->admin);
    foreach ($requests as [$method, $url, $data]) {
        $this->json($method, $url, $data)->assertForbidden();
    }
    expect(StoreSetting::count())->toBe(1);
});

test('creation sets actor IDs and persists empty social links as an object', function (): void {
    Sanctum::actingAs($this->admin);
    $this->postJson('/api/v1/dashboard/store-settings', $this->payload)->assertCreated()
        ->assertJsonPath('data.created_by', $this->admin->id)->assertJsonPath('data.updated_by', $this->admin->id);
    $row = StoreSetting::firstOrFail();
    expect(DB::table('store_settings')->value('social_links'))->toBe('{}');
    expect($row->created_at)->not->toBeNull()->and($row->updated_at)->not->toBeNull();
    expect($row->createdBy->id)->toBe($this->admin->id)->and($row->updatedBy->id)->toBe($this->admin->id);
});

test('a duplicate create is rejected before uploading files', function (): void {
    createStoreSettings();
    Sanctum::actingAs($this->admin);
    $this->post('/api/v1/dashboard/store-settings', $this->payload + ['logo' => UploadedFile::fake()->image('new.png')], ['Accept' => 'application/json'])->assertConflict();
    expect(StoreSetting::count())->toBe(1)->and(Storage::disk('documents')->allFiles())->toBe([]);
});

test('the database singleton index rejects a second record independently of the service', function (): void {
    createStoreSettings();
    expect(fn () => StoreSetting::create($this->payload))->toThrow(QueryException::class);
});

test('partial updates preserve omitted fields files links and the original creator', function (): void {
    $creator = DatabaseFixture::user(1);
    $settings = createStoreSettings(['created_by' => $creator->id, 'updated_by' => $creator->id]);
    Storage::disk('documents')->put('store_settings/logo/old.webp', 'original');
    Storage::disk('documents')->put('store_settings/favicon/old.png', 'original');
    Sanctum::actingAs($this->admin);
    $this->patchJson('/api/v1/dashboard/store-settings/'.$settings->id, ['name_en' => 'Updated name'])->assertOk();
    $settings->refresh();
    expect($settings->name_en)->toBe('Updated name')->and($settings->description_en)->toBe('Original description')
        ->and($settings->phone_number)->toBe('+966500000000')->and($settings->currency_code)->toBe('SAR')
        ->and($settings->logo_path)->toBe('documents/store_settings/logo/old.webp')
        ->and($settings->favicon_path)->toBe('documents/store_settings/favicon/old.png')
        ->and($settings->social_links->instagram)->toBe('https://instagram.com/original')
        ->and($settings->created_by)->toBe($creator->id)->and($settings->updated_by)->toBe($this->admin->id);
    Storage::disk('documents')->assertExists('store_settings/logo/old.webp');
    Storage::disk('documents')->assertExists('store_settings/favicon/old.png');
});

test('a partial request merges social links and null removes only the selected platform', function (): void {
    $settings = createStoreSettings();
    Sanctum::actingAs($this->admin);
    $url = '/api/v1/dashboard/store-settings/'.$settings->id;
    $this->patchJson($url, ['social_links' => ['instagram' => 'https://instagram.com/new']])->assertOk();
    expect($settings->fresh()->social_links->tiktok)->toBe('https://tiktok.com/@original');
    $this->patchJson($url, ['social_links' => ['instagram' => null]])->assertOk()->assertJsonMissingPath('data.social_links.instagram');
    $this->patchJson($url, ['social_links' => ['tiktok' => null]])->assertOk();
    expect(DB::table('store_settings')->value('social_links'))->toBe('{}');
});

test('nullable scalar fields can be cleared explicitly', function (): void {
    $settings = createStoreSettings();
    Sanctum::actingAs($this->admin);
    $this->patchJson('/api/v1/dashboard/store-settings/'.$settings->id, ['description_en' => null, 'phone_number' => null])->assertOk();
    expect($settings->fresh()->description_en)->toBeNull()->and($settings->fresh()->phone_number)->toBeNull();
});

test('invalid creation data returns bilingual validation errors', function (array $invalid): void {
    Sanctum::actingAs($this->admin);
    $this->postJson('/api/v1/dashboard/store-settings', array_replace($this->payload, $invalid))
        ->assertUnprocessable()->assertJsonStructure(['message' => ['ar', 'en'], 'error' => ['code', 'details']]);
    expect(StoreSetting::count())->toBe(0);
})->with([
    'empty Arabic name' => [['name_ar' => '   ']],
    'missing English name' => [['name_en' => null]],
    'invalid currency' => [['currency_code' => 'sar']],
    'invalid email' => [['email_address' => 'invalid']],
    'long phone' => [['phone_number' => str_repeat('1', 31)]],
    'unsafe social URL' => [['social_links' => ['instagram' => 'javascript:alert(1)']]],
    'list instead of links object' => [['social_links' => ['https://example.com']]],
    'nested social value' => [['social_links' => ['instagram' => ['url' => 'https://example.com']]]],
    'null social object' => [['social_links' => null]],
    'forged creator' => [['created_by' => 999]],
    'forged updater' => [['updated_by' => 999]],
    'injected logo path' => [['logo_path' => 'documents/someone-else.webp']],
]);

test('image uploads generate public URLs and replacement cleans only the replaced file', function (): void {
    Sanctum::actingAs($this->admin);
    $response = $this->post('/api/v1/dashboard/store-settings', $this->payload + [
        'logo' => UploadedFile::fake()->image('logo.png', 20, 20),
        'favicon' => UploadedFile::fake()->image('favicon.png', 32, 32),
    ], ['Accept' => 'application/json'])->assertCreated();
    $settings = StoreSetting::firstOrFail();
    $oldLogo = $settings->logo_path;
    $oldFavicon = $settings->favicon_path;
    expect($response->json('data.logo_url'))->toBe(asset($oldLogo));
    expect($response->json('data.favicon_url'))->toBe(asset($oldFavicon));
    $this->post('/api/v1/dashboard/store-settings/'.$settings->id, ['logo' => UploadedFile::fake()->image('replacement.jpg', 20, 20)], ['Accept' => 'application/json'])->assertOk();
    $settings->refresh();
    expect($settings->logo_path)->not->toBe($oldLogo)->and($settings->favicon_path)->toBe($oldFavicon);
    Storage::disk('documents')->assertMissing(substr($oldLogo, 10));
    Storage::disk('documents')->assertExists(substr($oldFavicon, 10));
    Storage::disk('documents')->assertExists(substr($settings->logo_path, 10));
});

test('invalid uploads preserve existing settings and files', function (string $field, string $kind): void {
    $settings = createStoreSettings();
    Sanctum::actingAs($this->admin);
    $file = match ($kind) {
        'script' => UploadedFile::fake()->create('bad.php', 1, 'application/x-httpd-php'),
        'large' => UploadedFile::fake()->image('large.png')->size(5121),
        default => UploadedFile::fake()->image('favicon.png', 513, 32),
    };
    $this->post('/api/v1/dashboard/store-settings/'.$settings->id, [$field => $file], ['Accept' => 'application/json'])->assertUnprocessable();
    expect($settings->fresh()->logo_path)->toBe('documents/store_settings/logo/old.webp');
    expect(Storage::disk('documents')->allFiles())->toBe([]);
})->with([['logo', 'script'], ['logo', 'large'], ['favicon', 'dimensions']]);

test('failed creation cleans uploaded files and hides internal errors', function (): void {
    $repository = Mockery::mock(StoreSettingsRepository::class);
    $repository->shouldReceive('exists')->once()->andReturnFalse();
    $repository->shouldReceive('create')->once()->andThrow(new RuntimeException('Private database detail'));
    app()->instance(StoreSettingsRepository::class, $repository);
    Sanctum::actingAs($this->admin);
    $response = $this->post('/api/v1/dashboard/store-settings', $this->payload + ['logo' => UploadedFile::fake()->image('logo.png')], ['Accept' => 'application/json'])->assertStatus(500);
    expect($response->getContent())->not->toContain('Private database detail');
    expect(Storage::disk('documents')->allFiles())->toBe([]);
});

test('failed replacement preserves old files and removes uploaded replacements', function (): void {
    $settings = createStoreSettings();
    Storage::disk('documents')->put('store_settings/logo/old.webp', 'original');
    $repository = Mockery::mock(StoreSettingsRepository::class);
    $repository->shouldReceive('findForUpdate')->once()->with($settings->id)->andReturn($settings);
    $repository->shouldReceive('update')->once()->andThrow(new RuntimeException('Private database detail'));
    app()->instance(StoreSettingsRepository::class, $repository);
    Sanctum::actingAs($this->admin);
    $this->post('/api/v1/dashboard/store-settings/'.$settings->id, ['logo' => UploadedFile::fake()->image('new.png')], ['Accept' => 'application/json'])->assertStatus(500);
    expect(Storage::disk('documents')->allFiles())->toBe(['store_settings/logo/old.webp']);
    expect($settings->fresh()->logo_path)->toBe('documents/store_settings/logo/old.webp');
});

test('updates merge against the current row rather than stale bound attributes', function (): void {
    $stale = createStoreSettings();
    StoreSetting::whereKey($stale->id)->update(['social_links' => json_encode(['instagram' => 'https://instagram.com/latest', 'youtube' => 'https://youtube.com/latest'])]);
    Sanctum::actingAs($this->admin);
    $updated = app(StoreSettingsService::class)->update($stale, ['social_links' => ['tiktok' => 'https://tiktok.com/@new']]);
    expect($updated->social_links->instagram)->toBe('https://instagram.com/latest')
        ->and($updated->social_links->youtube)->toBe('https://youtube.com/latest')
        ->and($updated->social_links->tiktok)->toBe('https://tiktok.com/@new');
});

test('delete removes settings and associated files and allows a new record', function (): void {
    $settings = createStoreSettings();
    Storage::disk('documents')->put('store_settings/logo/old.webp', 'old');
    Storage::disk('documents')->put('store_settings/favicon/old.png', 'old');
    Sanctum::actingAs($this->admin);
    $url = '/api/v1/dashboard/store-settings/'.$settings->id;
    $this->deleteJson($url)->assertOk()->assertJsonPath('data', null);
    expect(StoreSetting::count())->toBe(0)->and(Storage::disk('documents')->allFiles())->toBe([]);
    $this->deleteJson($url)->assertNotFound();
    $this->postJson('/api/v1/dashboard/store-settings', $this->payload)->assertCreated();
});

test('failed deletion does not remove the referenced files', function (): void {
    $settings = createStoreSettings();
    Storage::disk('documents')->put('store_settings/logo/old.webp', 'original');
    $repository = Mockery::mock(StoreSettingsRepository::class);
    $repository->shouldReceive('findForUpdate')->once()->andReturn($settings);
    $repository->shouldReceive('delete')->once()->andThrow(new RuntimeException('Delete failed'));
    app()->instance(StoreSettingsRepository::class, $repository);
    Sanctum::actingAs($this->admin);
    $this->deleteJson('/api/v1/dashboard/store-settings/'.$settings->id)->assertStatus(500);
    Storage::disk('documents')->assertExists('store_settings/logo/old.webp');
    expect(StoreSetting::count())->toBe(1);
});

test('legacy routes expose the same protected feature', function (): void {
    Sanctum::actingAs($this->admin);
    $this->postJson('/api/dashboard/store-settings', $this->payload)->assertCreated();
    $this->getJson('/api/client/store-settings')->assertOk()->assertJsonMissingPath('data.created_by');
});

test('the public resource performs no extra queries during serialization', function (): void {
    createStoreSettings();
    $queries = [];
    DB::listen(function ($query) use (&$queries): void {
        $queries[] = $query->sql;
    });
    $this->getJson('/api/v1/client/store-settings')->assertOk();
    expect($queries)->toHaveCount(1);
});
