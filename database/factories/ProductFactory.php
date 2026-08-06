<?php

namespace Database\Factories;

use \App\Models\Categori;
use \App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Categori::factory(),
            'supplier_id' => Supplier::factory(),
            'name' => $this->faker->word(),
            'sku' => $this->faker->unique()->ean13(),
            'description' => $this->faker->sentence(),
            'purchase_price' => $this->faker->randomFloat(2, 10, 1000),
            'selling_price' => $this->faker->randomFloat(2, 10, 1000),
            'image' => $this->faker->imageUrl(640, 480),
            'stock' => $this->faker->numberBetween(0, 100),
        ];
    }
}
