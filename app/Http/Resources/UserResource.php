<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $private = $request->user() && ((int) $request->user()->getAuthIdentifier() === (int) $this->id || (int) $request->user()->user_type === 1);

        return [
            'id' => $this->id ?? null,
            'full_name' => $this->full_name ?? null,
            'user_name' => $this->user_name ?? null,
            'phone_number' => $this->when($private, fn () => $this->phone_number),
            'email_address' => $this->when($private, fn () => $this->email_address),
            'user_status' => $this->when($private, fn () => $this->status),
            'user_type' => $this->when($private, fn () => $this->user_type),
            'user_addresses' => AddressResource::collection($this->whenLoaded('addresses')),
            'user_orders' => OrderResource::collection($this->whenLoaded('orders')),
            'user_carts' => CartProductResource::collection($this->whenLoaded('cart')),
            'user_wishlists' => UserWishListResource::collection($this->whenLoaded('wishlist')),
            'user_reviews' => ProductReviewResource::collection($this->whenLoaded('reviews')),

            'created_by' => $this->when($private, fn () => $this->created_by),
            'updated_by' => $this->when($private, fn () => $this->updated_by),

            'created_at' => $this->created_at?->format('Y-m-d H:i:s') ?? null,
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s') ?? null,
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s') ?? null,
        ];
    }
}
