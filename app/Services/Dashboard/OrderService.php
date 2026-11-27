<?php

declare(strict_types=1);

namespace App\Services\Dashboard;

use App\Models\Order;
use App\Repositories\Dashboard\OrderRepository;
use App\Services\ActingUserService;
use App\Services\LoggingService;
use App\Services\Products\InventoryService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(private OrderRepository $repository, private InventoryService $inventory,
        private ActingUserService $actor, private LoggingService $logging) {}

    public function getOrdersList(): Collection
    {
        return $this->repository->getOrdersList();
    }

    public function getOrderDetails(Order $order): Order
    {
        return $this->repository->getOrderDetails($order);
    }

    public function confirmOrder(array $data, Order $order): Order
    {
        return DB::transaction(function () use ($data, $order): Order {
            $order = $this->repository->lockOrder($order);
            $from = (int) $order->status;
            $to = (int) $data['status'];
            if ($from === $to) {
                return $this->repository->getOrderDetails($order);
            }
            // Rejection is allowed before stock deduction. Approved orders progress forward only.
            $transitions = [1 => [2, 5], 2 => [3], 3 => [4]];
            abort_unless(in_array($to, $transitions[$from] ?? [], true), 409);
            if ($to === 2) {
                $quantities = [];
                foreach ($order->details as $detail) {
                    $quantities[(int) $detail->variant_id] = ($quantities[(int) $detail->variant_id] ?? 0) + (int) $detail->quantity;
                }
                abort_if($quantities === [], 409);
                $this->inventory->deduct($quantities);
            }
            $order = $this->repository->confirmOrder($order, $this->actor->attributes(['status' => $to], true));
            DB::afterCommit(function (): void {
                $this->logging->record('UpdateOrderStatus', $this->actor->id(), 'SUCCESS');
            });

            return $this->repository->getOrderDetails($order);
        });
    }
}
