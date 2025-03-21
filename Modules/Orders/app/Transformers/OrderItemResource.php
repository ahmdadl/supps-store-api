<?php

namespace Modules\Orders\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "order_id" => $this->order_id,
            "product_id" => $this->product_id,
            "quantity" => $this->quantity,
            "totals" => $this->totals,
            "created_at" => $this->created_at,
            "product" => $this->whenLoaded("product", $this->product),
        ];
    }
}
