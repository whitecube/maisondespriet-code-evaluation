<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Promotion;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Promotion>
 */
class PromotionFactory extends Factory
{
    protected $model = Promotion::class;

    public function definition()
    {
        return [
            'percentage' => $this->faker->numberBetween(5, 50), 
            'category_id' => Category::factory(), 
            'type' => $this->faker->randomElement([
                ClientType::Normal->value,
                ClientType::Vip->value,
                ClientType::Wholesaler->value,
            ]), // Type de client
        ];
    }
}
