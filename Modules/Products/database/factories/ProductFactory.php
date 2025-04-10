<?php

namespace Modules\Products\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Brands\Models\Brand;
use Modules\Categories\Models\Category;
use Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Products\Models\Product::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $price = fake()->randomFloat(2, 1.0, 500.99);

        return [
            "category_id" => fn() => Category::factory(),
            "brand_id" => fn() => Brand::factory(),
            "title" => [
                "en" => fake()->sentence,
                "ar" => fake()->sentence,
            ],
            "description" => [
                "en" => fake()->paragraph(),
                "ar" => fake()->paragraph,
            ],
            "is_main" => fake()->boolean(20),
            "images" => null,
            "price" => $price,
            "salePrice" => fake()->boolean(30) ? $price * 0.8 : null,
            "stock" => fake()->numberBetween(10, 100),
            "sku" => "SKU-" . strtoupper(Str::random(6)),
            "is_active" => true,
            "meta_title" => [
                "en" => fake()->sentence,
                "ar" => fake()->sentence,
            ],
            "meta_description" => [
                "en" => fake()->sentence,
                "ar" => fake()->sentence,
            ],
            "meta_keywords" => ["prod", "cool", "food"],
        ];
    }

    public function inactive(): Factory
    {
        return $this->state(
            fn(array $attributes) => [
                "is_active" => false,
            ]
        );
    }
}
