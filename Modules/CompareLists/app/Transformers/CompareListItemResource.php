<?php

namespace Modules\CompareLists\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompareListItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "compare_list_id" => $this->compare_list_id,
            "product_id" => $this->product_id,
            "product" => $this->whenLoaded("product", $this->product),
            // "created_at" => $this->created_at,
        ];
    }
}
