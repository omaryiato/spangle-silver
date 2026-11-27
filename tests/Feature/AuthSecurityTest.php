<?php

declare(strict_types=1);
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;
use Tests\Support\DatabaseFixture as Fixture;

beforeEach(function (): void {
    Fixture::create();
});
test('login uses actual columns and returns a hashed expiring bearer token', function (): void {
    $user = Fixture::user();
    $response = $this->postJson('/api/login', ['email' => $user->email_address, 'password' => 'Test-password-123'])->assertOk();
    expect(Hash::check('Test-password-123', $user->password))->toBeTrue();
    expect($response->json('data.token'))->toBeString();
    expect($response->json('data.user.password'))->toBeNull();
    expect(DB::table('personal_access_tokens')->value('expires_at'))->not->toBeNull();
    expect(DB::table('personal_access_tokens')->value('token'))->not->toBe($response->json('data.token'));
});
test('signup assigns customer role and hashes passwords', function (): void {
    $response = $this->postJson('/api/signup', ['full_name' => 'New User', 'user_name' => 'new', 'email_address' => 'new@example.com', 'password' => 'Strong-password-123', 'password_confirmation' => 'Strong-password-123'])->assertCreated();
    $user = User::where('email_address', 'new@example.com')->firstOrFail();
    expect($user->user_type)->toBe(0)->and($user->status)->toBe(1);
    expect(Hash::check('Strong-password-123', $user->password))->toBeTrue();
    expect($response->json('data.token'))->toBeString();
});
test('signup rejects privilege escalation with bilingual errors', function (): void {
    $this->postJson('/api/signup', ['user_type' => 1])->assertUnprocessable()->assertJsonStructure(['status', 'message' => ['ar', 'en'], 'error' => ['code', 'details']]);
    expect(User::count())->toBe(0);
});
test('wrong password unknown user and inactive user all fail safely', function (): void {
    $user = Fixture::user();
    $this->postJson('/api/login', ['email_address' => $user->email_address, 'password' => 'wrong'])->assertUnauthorized();
    $this->postJson('/api/login', ['email_address' => 'unknown@example.com', 'password' => 'wrong'])->assertUnauthorized();
    $user->update(['status' => 0]);
    $this->postJson('/api/login', ['email_address' => $user->email_address, 'password' => 'Test-password-123'])->assertUnauthorized();
});
test('personal endpoints require authentication and customer cannot enter dashboard', function (): void {
    $this->getJson('/api/client/get-user-cart-list')->assertUnauthorized();
    Sanctum::actingAs(Fixture::user());
    $this->getJson('/api/dashboard/category')->assertForbidden();
});
test('inactive accounts cannot continue with previously issued tokens', function (): void {
    $user = Fixture::user();
    $user->update(['status' => 0]);
    Sanctum::actingAs($user);
    $this->getJson('/api/me')->assertForbidden();
});
test('login attempts are rate limited', function (): void {
    $user = Fixture::user();
    for ($i = 0; $i < 5; $i++) {
        $this->postJson('/api/login', ['email' => $user->email_address, 'password' => 'wrong'])->assertUnauthorized();
    }
    $this->postJson('/api/login', ['email' => $user->email_address, 'password' => 'wrong'])->assertStatus(429);
});
test('refresh rotates token and logout revokes it', function (): void {
    $user = Fixture::user();
    $token = $this->postJson('/api/login', ['email' => $user->email_address, 'password' => 'Test-password-123'])->assertOk()->json('data.token');
    $response = $this->withToken($token)->postJson('/api/refresh')->assertOk();
    $next = $response->json('data.token');
    expect($next)->not->toBe($token);
    expect(DB::table('personal_access_tokens')->count())->toBe(1);
    // Reset guards between requests to simulate independent HTTP requests.
    app('auth')->forgetGuards();
    $this->withToken($token)->getJson('/api/me')->assertUnauthorized();
    app('auth')->forgetGuards();
    $this->withToken($next)->postJson('/api/logout')->assertOk();
    expect(DB::table('personal_access_tokens')->count())->toBe(0);
});
test('client IDs cannot read another account or mutate another cart', function (): void {
    $user = Fixture::user();
    $other = Fixture::user();
    $catalog = Fixture::catalog();
    $cart = DB::table('cart_products')->insertGetId(['user_id' => $other->id, 'variant_id' => $catalog['variant'], 'quantity' => 2]);
    Sanctum::actingAs($user);
    $this->getJson('/api/client/get-user-orders/'.$other->id)->assertForbidden();
    $this->postJson('/api/client/update-cart-details/'.$cart, ['quantity' => 1])->assertForbidden();
    $this->postJson('/api/client/delete-cart-list', ['cart_id' => $cart])->assertNotFound();
    expect(DB::table('cart_products')->where('id', $cart)->value('quantity'))->toBe('2');
});
test('forged cart owner is ignored and deletion by record does not clear the whole cart', function (): void {
    $user = Fixture::user();
    $other = Fixture::user();
    $catalog = Fixture::catalog();
    Sanctum::actingAs($user);
    $response = $this->postJson('/api/client/add-to-cart', ['user_id' => $other->id, 'variant_id' => $catalog['variant'], 'quantity' => 1])->assertOk();
    $id = $response->json('data.id');
    expect((int) DB::table('cart_products')->where('id', $id)->value('user_id'))->toBe($user->id);
    $this->postJson('/api/client/delete-cart-list', ['cart_id' => $id, 'user_id' => $other->id])->assertOk();
    expect(DB::table('cart_products')->count())->toBe(0);
});

test('expired bearer token is rejected', function (): void {
    $user = Fixture::user();
    $token = $user->createToken('expired', ['*'], now()->subMinute());
    $this->withToken($token->plainTextToken)->getJson('/api/me')->assertUnauthorized();
});
test('versioned routes use the same authentication protections', function (): void {
    $user = Fixture::user();
    $this->getJson('/api/v1/client/get-user-orders')->assertUnauthorized();
    $this->postJson('/api/v1/login', ['email' => $user->email_address, 'password' => 'Test-password-123'])->assertOk()->assertJsonStructure(['data' => ['user', 'token', 'expires_at']]);
});

test('bcrypt passwords over 72 bytes are rejected before hashing', function (): void {
    $password = str_repeat('x', 73);
    $this->postJson('/api/signup', ['full_name' => 'Test', 'user_name' => 'too-long', 'email_address' => 'long@example.com', 'password' => $password, 'password_confirmation' => $password])->assertUnprocessable();
    expect(User::count())->toBe(0);
});

test('logging failure never interrupts authentication', function (): void {
    Log::shouldReceive('build')->andThrow(new RuntimeException('Log disk unavailable'));
    Log::shouldReceive('warning')->once();
    $user = Fixture::user();
    $this->postJson('/api/login', ['email' => $user->email_address, 'password' => 'Test-password-123'])->assertOk();
});
