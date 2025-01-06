<?php

namespace Database\Factories;

use App\Models\RoomTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomTypesFactory extends Factory
{
    protected $model = RoomTypes::class;

    public function definition(): array
    {
        // Solo permitimos los tipos específicos según requerimientos
        return [
            'name' => $this->faker->randomElement(['ESTANDAR', 'JUNIOR', 'SUITE'])
        ];
    }
}