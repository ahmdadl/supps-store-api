<?php

namespace Modules\Orders\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Carts\Casts\CartTotalsCast;
use Spatie\Translatable\HasTranslations;
use Modules\Orders\Database\Factories\OrderItemFactory;
use Modules\Products\Models\Product;

#[UseFactory(OrderItemFactory::class)]
class OrderItem extends Model
{
    /** @use HasFactory<OrderItemFactory> */
    use HasFactory, HasUlids, HasTranslations;

    protected array $translatable = ["title"];

    protected function casts(): array
    {
        return [
            "quantity" => "int",
            "totals" => CartTotalsCast::class,
        ];
    }

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
