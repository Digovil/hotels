<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Primero los catálogos base
        // \App\Models\RoomTypes::factory(3)->create();
        // \App\Models\AccommodationTypes::factory(4)->create();

        // Luego los hoteles
        \App\Models\Hotels::factory(1)->create();

        // // Después las reglas de acomodación
        // \App\Models\RoomAccommodationRules::factory(7)->create();

        // Finalmente las habitaciones de hotel
        \App\Models\HotelRooms::factory(2)->valid()->create();
    }
}