<?php

declare(strict_types=1);
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\Support\DatabaseFixture as Fixture;

beforeEach(function (): void {
    Fixture::create();
    $this->customer = Fixture::user();
    $this->admin = Fixture::user(1);
    $this->catalog = Fixture::catalog();
});
function orderPayload(array $catalog): array
{
    return ['shipping_id' => $catalog['shipping'], 'order_details' => [['variant_id' => $catalog['variant'], 'quantity' => 2, 'unit_price' => 0, 'total_price' => 0]],
        'subtotal' => 0, 'discount' => 9999, 'shipping_cost' => 0, 'total_price' => 0, 'status' => 4,
        'snap_address' => 'Test address', 'snap_city' => 'Amman', 'snap_country' => 'Jordan', 'snap_phone' => '0500000000'];
}
test('checkout calculates prices and assigns owner and actor server side', function (): void {
    Sanctum::actingAs($this->customer);
    $data = orderPayload($this->catalog) + ['user_id' => $this->admin->id, 'created_by' => $this->admin->id];
    $response = $this->postJson('/api/client/place-new-order', $data)->assertOk();
    $order = Order::findOrFail($response->json('data.id'));
    expect((int) $order->user_id)->toBe($this->customer->id)->and((int) $order->created_by)->toBe($this->customer->id)->and((int) $order->status)->toBe(1);
    expect((float) $order->subtotal)->toBe(200.0)->and((float) $order->shipping_cost)->toBe(5.0)->and((float) $order->discount)->toBe(0.0)->and((float) $order->total_price)->toBe(205.0);
    expect((float) $order->details->first()->total_price)->toBe(200.0);
});
test('checkout rejects another user address and does not write partial orders', function (): void {
    $address = DB::table('addresses')->insertGetId(['user_id' => $this->admin->id, 'full_name' => 'Other']);
    Sanctum::actingAs($this->customer);
    $this->postJson('/api/client/place-new-order', orderPayload($this->catalog) + ['address_id' => $address])->assertNotFound();
    expect(Order::count())->toBe(0)->and(DB::table('orders_details')->count())->toBe(0);
});
test('approval deducts stock exactly once and enforces forward transitions', function (): void {
    Sanctum::actingAs($this->customer);
    $id = $this->postJson('/api/client/place-new-order', orderPayload($this->catalog))->assertOk()->json('data.id');
    Sanctum::actingAs($this->admin);
    $this->postJson('/api/dashboard/confirm-order/'.$id, ['status' => 2, 'subtotal' => 1])->assertOk();
    $this->postJson('/api/dashboard/confirm-order/'.$id, ['status' => 2])->assertOk();
    expect((int) DB::table('product_variants')->value('stock'))->toBe(8);
    expect((float) Order::findOrFail($id)->subtotal)->toBe(200.0);
    $this->postJson('/api/dashboard/confirm-order/'.$id, ['status' => 4])->assertConflict();
    $this->postJson('/api/dashboard/confirm-order/'.$id, ['status' => 3])->assertOk();
    $this->postJson('/api/dashboard/confirm-order/'.$id, ['status' => 4])->assertOk();
    $this->postJson('/api/dashboard/confirm-order/'.$id, ['status' => 2])->assertConflict();
    expect((int) DB::table('product_variants')->value('stock'))->toBe(8);
});
test('insufficient stock rolls back approval and order status', function (): void {
    Sanctum::actingAs($this->customer);
    $id = $this->postJson('/api/client/place-new-order', orderPayload($this->catalog))->assertOk()->json('data.id');
    DB::table('product_variants')->update(['stock' => 1]);
    Sanctum::actingAs($this->admin);
    $this->postJson('/api/dashboard/confirm-order/'.$id, ['status' => 2])->assertConflict();
    expect((int) Order::findOrFail($id)->status)->toBe(1)->and((int) DB::table('product_variants')->value('stock'))->toBe(1);
});
test('only the owning customer can cancel a pending order', function (): void {
    Sanctum::actingAs($this->customer);
    $id = $this->postJson('/api/client/place-new-order', orderPayload($this->catalog))->assertOk()->json('data.id');
    Sanctum::actingAs($this->admin);
    $this->postJson('/api/client/orders/'.$id.'/cancel')->assertNotFound();
    Sanctum::actingAs($this->customer);
    $this->postJson('/api/client/orders/'.$id.'/cancel')->assertOk();
    expect((int) Order::findOrFail($id)->status)->toBe(6)->and((int) DB::table('product_variants')->value('stock'))->toBe(10);
    Sanctum::actingAs($this->admin);
    $this->postJson('/api/dashboard/confirm-order/'.$id, ['status' => 2])->assertConflict();
});
test('approved order cannot be canceled by customer', function (): void {
    Sanctum::actingAs($this->customer);
    $id = $this->postJson('/api/client/place-new-order', orderPayload($this->catalog))->assertOk()->json('data.id');
    Sanctum::actingAs($this->admin);
    $this->postJson('/api/dashboard/confirm-order/'.$id, ['status' => 2])->assertOk();
    Sanctum::actingAs($this->customer);
    $this->postJson('/api/client/orders/'.$id.'/cancel')->assertConflict();
});
test('coupon expiry limits and usage counts are enforced', function (): void {
    $coupon = DB::table('coupons')->insertGetId(['code' => 'SAVE', 'discount_amount' => 20, 'minimum_order_amount' => 50, 'max_usage' => 1, 'used_count' => 0, 'status' => 1, 'expires_at' => now()->addDay()]);
    Sanctum::actingAs($this->customer);
    $id = $this->postJson('/api/client/place-new-order', orderPayload($this->catalog) + ['coupon_id' => $coupon])->assertOk()->json('data.id');
    expect((float) Order::findOrFail($id)->total_price)->toBe(185.0)->and(DB::table('coupon_usage')->count())->toBe(1);
    $this->postJson('/api/client/place-new-order', orderPayload($this->catalog) + ['coupon_id' => $coupon])->assertConflict();
    expect(Order::count())->toBe(1);
});
