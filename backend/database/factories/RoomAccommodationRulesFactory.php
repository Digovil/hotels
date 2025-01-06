<?php

namespace Database\Factories;

use App\Models\RoomAccommodationRules;
use App\Models\RoomTypes;
use App\Models\AccommodationTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomAccommodationRulesFactory extends Factory
{
    protected $model = RoomAccommodationRules::class;

    public function definition(): array
    {
        $roomType = RoomTypes::inRandomOrder()->first();
        $accommodationType = null;

        // Aplicar reglas según el tipo de habitación
        switch($roomType->name) {
            case 'ESTANDAR':
                $accommodationType = AccommodationTypes::whereIn('name', ['SENCILLA', 'DOBLE'])->inRandomOrder()->first();
                break;
            case 'JUNIOR':
                $accommodationType = AccommodationTypes::whereIn('name', ['TRIPLE', 'CUADRUPLE'])->inRandomOrder()->first();
                break;
            case 'SUITE':
                $accommodationType = AccommodationTypes::whereIn('name', ['SENCILLA', 'DOBLE', 'TRIPLE'])->inRandomOrder()->first();
                break;
        }

        return [
            'room_type_id' => $roomType->room_types_id,
            'accommodation_type_id' => $accommodationType->accommodation_types_id
        ];
    }
}