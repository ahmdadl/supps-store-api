<?php

namespace Modules\Carts\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Addresses\Transformers\AddressResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            // 'cartable_id' => $this->customer_id,
            // 'cartable_type' => $this->customer_type,
            "address_id" => $this->address_id,
            "coupon_id" => $this->coupon_id,
            "totals" => $this->totals,
            // "user" => $this->whenLoaded("cartable", $this->cartable),
            "address" => new AddressResource(
                $this->whenLoaded("address", $this->address)
            ),
            "coupon" => $this->whenLoaded("coupon", $this->coupon),
            "items" => CartItemResource::collection(
                $this->whenLoaded(
                    "items",
                    CartItemResource::collection($this->items)
                )
            ),
        ];
    }
}
