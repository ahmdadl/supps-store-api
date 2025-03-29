<?php

namespace Modules\Products\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Brands\Models\Brand;
use Modules\Carts\Models\CartItem;
use Modules\Categories\Models\Category;
use Modules\Core\Models\Scopes\HasActiveState;
use Modules\Core\Models\Scopes\HasMetaTags;
use Modules\Products\Database\Factories\ProductFactory;
use Spatie\Translatable\HasTranslations;

#[UseFactory(ProductFactory::class)]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory,
        HasUlids,
        HasActiveState,
        HasMetaTags,
        HasTranslations,
        Sluggable,
        SoftDeletes;

    /**
     * translatable fields
     *
     * @var array<int, string>
     */
    public array $translatable = ["title", "description"];

    /**
     * get route key name
     */
    public function getRouteKeyName(): string
    {
        return "slug";
    }

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            "slug" => [
                "source" => "title",
            ],
        ];
    }

    /**
     * cast fields
     */
    protected function casts(): array
    {
        return [
            "is_main" => "boolean",
            "price" => "float",
            "salePrice" => "float",
            "images" => "array",
        ];
    }

    /**
     * boot model
     */
    public static function boot(): void
    {
        parent::boot();

        static::creating(function (Product $product) {
            if (empty($product->salePrice) || $product->salePrice <= 0) {
                $product->salePrice = $product->price;
            }
        });
    }

    /**
     * product with discount
     *
     * @param  Builder<Product>  $query
     */
    public function scopeHasDiscount(Builder $query): void
    {
        $query
            ->whereNotNull("salePrice")
            ->whereColumn("salePrice", "<", "price");
    }

    /**
     * product has discount
     *
     * @return Attribute<float, void>
     */
    public function isDiscounted(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->salePrice < $this->price
        )->shouldCache();
    }

    /**
     * product discounted price
     *
     * @return Attribute<float, void>
     */
    public function discountedPrice(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->salePrice < $this->price
                ? round($this->price - $this->salePrice, 2)
                : 0.0
        )->shouldCache();
    }

    /**
     * product has stock
     *
     * @return Attribute<int, void>
     */
    public function hasStock(): Attribute
    {
        return Attribute::make(get: fn() => $this->stock > 0);
    }

    /** Relations */

    /**
     * parent category
     *
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * parent brand
     *
     * @return BelongsTo<Brand, $this>
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(related: Brand::class);
    }

    /**
     * product cart items
     * @return HasMany<CartItem, $this>
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }
}
