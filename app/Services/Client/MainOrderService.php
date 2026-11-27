<?php

declare(strict_types=1);

namespace App\Services\Client;

use App\Models\Order;
use App\Repositories\Client\MainOrderRepository;
use App\Services\ActingUserService;
use App\Services\Dashboard\AddressService;
use App\Services\Dashboard\CouponService;
use App\Services\Dashboard\ShippingMethodService;
use App\Services\LoggingService;
use App\Services\Products\InventoryService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class MainOrderService
{
    public function __construct(private MainOrderRepository $repository, private ActingUserService $actor,
        private AddressService $addresses, private CouponService $coupons, private ShippingMethodService $shipping,
        private InventoryService $inventory, private LoggingService $logging) {}

    public function getUserOrders(): Collection
    {
        return $this->repository->getUserOrders($this->actor->id());
    }

    /** Prices are derived from persisted data and calculated in integer cents. */
    public function addNewOrder(array $data): Order
    {
        return DB::transaction(function () use ($data): Order {
            $userId = $this->actor->id();
            $quantities = [];
            foreach ($data['order_details'] as $line) {
                $quantities[(int) $line['variant_id']] = (int) $line['quantity'];
            }
            $variants = $this->inventory->lockAvailable($quantities);
            $subtotal = 0;
            $details = [];
            foreach ($quantities as $id => $quantity) {
                $variant = $variants->get($id);
                $price = (int) round((float) ($variant->price ?? $variant->product->product_price) * 100);
                abort_if($price < 0 || $quantity > 1000000, 422);
                $lineTotal = $price * $quantity;
                $subtotal += $lineTotal;
                abort_if($lineTotal > 9999999999 || $subtotal > 9999999999, 422);
                $details[] = $this->actor->attributes(['variant_id' => $id, 'quantity' => $quantity,
                    'unit_price' => $price / 100, 'total_price' => $lineTotal / 100]);
            }
            $shipping = $this->shipping->getActiveMethod((int) $data['shipping_id']);
            $shippingCents = (int) round((float) $shipping->price * 100);
            $coupon = isset($data['coupon_id']) ? $this->coupons->getAvailableCoupon((int) $data['coupon_id'], $userId, $subtotal) : null;
            $discount = $coupon ? min($subtotal, (int) round((float) $coupon->discount_amount * 100)) : 0;
            $address = isset($data['address_id']) ? $this->addresses->getOwnedAddress((int) $data['address_id'], $userId) : null;
            $payload = array_intersect_key($data, array_flip(['notes', 'snap_user_name', 'snap_address', 'snap_city', 'snap_country', 'snap_phone', 'snap_email', 'snap_postal_code']));
            if ($address) {
                $payload = array_merge($payload, ['snap_user_name' => $address->full_name, 'snap_address' => $address->address_line,
                    'snap_city' => $address->city, 'snap_country' => $address->country, 'snap_phone' => $address->phone, 'snap_postal_code' => $address->postal_code]);
            }
            abort_if($subtotal + $shippingCents - $discount > 9999999999, 422);
            $payload = array_merge($payload, ['user_id' => $userId, 'address_id' => $address?->id, 'shipping_id' => $shipping->id,
                'subtotal' => $subtotal / 100, 'shipping_cost' => $shippingCents / 100, 'discount' => $discount / 100,
                'total_price' => ($subtotal + $shippingCents - $discount) / 100, 'status' => 1]);
            $order = $this->repository->addNewOrder($this->actor->attributes($payload));
            $this->repository->addOrderDetails($order, $details);
            if ($coupon) {
                $this->coupons->recordOrderUsage($coupon, $userId, (int) $order->id);
            }
            DB::afterCommit(function () use ($userId): void {
                $this->logging->record('PlaceOrder', $userId, 'SUCCESS');
            });

            return $this->repository->getOrderDetails($order);
        });
    }

    public function cancelOrder(Order $order): Order
    {
        return DB::transaction(function () use ($order): Order {
            $order = $this->repository->lockOwned($order, $this->actor->id());
            abort_unless((int) $order->status === 1, 409);
            $order = $this->repository->updateStatus($order, $this->actor->attributes(['status' => 6], true));
            DB::afterCommit(function (): void {
                $this->logging->record('CancelOrder', $this->actor->id(), 'SUCCESS');
            });

            return $order;
        });
    }
}
