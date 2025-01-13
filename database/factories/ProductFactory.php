<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(), 
            'price_selling' => $this->faker->randomFloat(2, 10, 500), 
            'price_acquisition' => $this->faker->randomFloat(2, 10, 500), 
            'main_category_id' => Category::factory(), 
        ];
    }
}
