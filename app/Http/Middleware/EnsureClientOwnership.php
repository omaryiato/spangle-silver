<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\CartProduct;
use App\Models\Order;
use App\Models\User;
use App\Models\UserWishlist;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureClientOwnership
{
    public function handle(Request $request, Closure $next): Response
    {
        $actorId = (int) $request->user()->getAuthIdentifier();
        $target = $request->route('user');
        if ($target !== null) {
            abort_unless((int) ($target instanceof User ? $target->getKey() : $target) === $actorId, 403);
        }
        $order = $request->route('order');
        if ($order instanceof Order) {
            abort_unless((int) $order->user_id === $actorId, 404);
        }
        $cart = $request->route('cartProduct');
        if ($cart instanceof CartProduct) {
            abort_unless((int) $cart->user_id === $actorId, 403);
        }
        foreach (['cart_id' => CartProduct::class, 'wishlist_id' => UserWishlist::class] as $key => $model) {
            if ($request->filled($key)) {
                $id = $request->input($key);
                abort_unless(is_scalar($id) && filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) !== false, 422);
                abort_unless($model::whereKey($id)->where('user_id', $actorId)->exists(), 404);
            }
        }

        return $next($request);
    }
}
