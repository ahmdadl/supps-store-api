<?php

namespace Modules\Carts\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Carts\Casts\CartTotalsCast;
use Modules\Carts\Database\Factories\CartFactory;

#[UseFactory(CartFactory::class)]
class Cart extends Model
{
    /** @use HasFactory<CartFactory> */
    use HasFactory, HasUlids;

    /**
     * cast fields
     */
    protected function casts(): array
    {
        return [
            "totals" => CartTotalsCast::class,
        ];
    }

    /**
     * cart owner
     * @return MorphTo<\Modules\Users\Models\User|\Modules\Guests\Models\Guest, $this>
     */
    public function cartable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * cart items
     * @return HasMany<CartItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }
}
