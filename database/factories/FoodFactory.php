<?php

namespace Database\Factories;

use App\Models\Food;
use Illuminate\Database\Eloquent\Factories\Factory;

class FoodFactory extends Factory
{
    protected $model = Food::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'ingredients' => $this->faker->text,
            'price' => $this->faker->numberBetween(100000, 200000),
            'rating' => $this->faker->numberBetween(1, 5),
            'types' => $this->faker->randomElement(['Breakfast', 'Lunch', 'Dinner']),
            'picturePath' => $this->faker->imageUrl(),
        ];
    }
}
