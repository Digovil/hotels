<?php

namespace Database\Factories;

use App\Models\HotelRooms;
use App\Models\Hotels;
use App\Models\RoomTypes;
use App\Models\AccommodationTypes;
use App\Models\RoomAccommodationRules;
use Illuminate\Database\Eloquent\Factories\Factory;

class HotelRoomsFactory extends Factory
{
    protected $model = HotelRooms::class;

    public function definition(): array
    {
        // Obtener un hotel aleatorio
        $hotel = Hotels::inRandomOrder()->first();
        
        // Obtener una regla de acomodación válida aleatoria
        $rule = RoomAccommodationRules::inRandomOrder()->first();
        
        // Calcular cuántas habitaciones están ya asignadas al hotel
        $assignedRooms = HotelRooms::where('hotel_id', $hotel->hotels_id)->sum('quantity');
        
        // Calcular cuántas habitaciones quedan disponibles
        $availableRooms = $hotel->total_rooms - $assignedRooms;
        
        // Generar una cantidad aleatoria que no exceda las habitaciones disponibles
        $quantity = $availableRooms > 0 ? $this->faker->numberBetween(1, min(20, $availableRooms)) : 0;

        return [
            'hotel_id' => $hotel->hotels_id,
            'room_type_id' => $rule->room_type_id,
            'accommodation_type_id' => $rule->accommodation_type_id,
            'quantity' => $quantity
        ];
    }

    /**
     * Indica que esta configuración de habitación debe ser válida según las reglas
     */
    public function valid(): Factory
    {
        return $this->state(function (array $attributes) {
            $roomType = RoomTypes::find($attributes['room_type_id']);
            $validAccommodations = [];

            switch($roomType->name) {
                case 'ESTANDAR':
                    $validAccommodations = ['SENCILLA', 'DOBLE'];
                    break;
                case 'JUNIOR':
                    $validAccommodations = ['TRIPLE', 'CUADRUPLE'];
                    break;
                case 'SUITE':
                    $validAccommodations = ['SENCILLA', 'DOBLE', 'TRIPLE'];
                    break;
            }

            $accommodationType = AccommodationTypes::whereIn('name', $validAccommodations)
                ->inRandomOrder()
                ->first();

            return [
                'accommodation_type_id' => $accommodationType->accommodation_types_id
            ];
        });
    }
}