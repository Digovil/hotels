<?php

namespace Database\Factories;

use App\Models\AccommodationTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccommodationTypesFactory extends Factory
{
    protected $model = AccommodationTypes::class;

    public function definition(): array
    {
        // Solo permitimos los tipos específicos según requerimientos
        return [
            'name' => $this->faker->randomElement(['SENCILLA', 'DOBLE', 'TRIPLE', 'CUADRUPLE'])
        ];
    }
}