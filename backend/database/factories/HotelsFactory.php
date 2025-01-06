<?php

namespace Database\Factories;

use App\Models\Hotels;
use Illuminate\Database\Eloquent\Factories\Factory;

class HotelsFactory extends Factory
{
    protected $model = Hotels::class;

    public function definition(): array
    {
        return [
            'name' => 'DECAMERON ' . $this->faker->unique()->city,
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'tax_id' => $this->faker->unique()->numerify('#########-#'),
            'total_rooms' => $this->faker->numberBetween(30, int2: 200)
        ];
    }
}