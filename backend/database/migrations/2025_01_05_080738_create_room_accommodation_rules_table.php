<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_accommodation_rules', function (Blueprint $table) {
            $table->uuid('room_accommodation_rules_id')->primary();
            $table->foreignUuid('room_type_id')->references('room_types_id')->on('room_types');
            $table->foreignUuid('accommodation_type_id')->references('accommodation_types_id')->on('accommodation_types');
            $table->timestamp('created_at')->default(now());
            $table->timestamp('updated_at')->default(now());

            $table->unique(['room_type_id', 'accommodation_type_id']);
        });

        $this->insertDefaultRules();
    }

    public function down(): void
    {
        Schema::dropIfExists('room_accommodation_rules');
    }

    private function insertDefaultRules(): void
    {
        $roomTypes = DB::table('room_types')->get();
        $accommodationTypes = DB::table('accommodation_types')->get();

        $rules = [];
        
        foreach ($roomTypes as $roomType) {
            foreach ($accommodationTypes as $accommodationType) {
                if (
                    ($roomType->name === 'ESTANDAR' && in_array($accommodationType->name, ['SENCILLA', 'DOBLE'])) ||
                    ($roomType->name === 'JUNIOR' && in_array($accommodationType->name, ['TRIPLE', 'CUADRUPLE'])) ||
                    ($roomType->name === 'SUITE' && in_array($accommodationType->name, ['SENCILLA', 'DOBLE', 'TRIPLE']))
                ) {
                    $rules[] = [
                        'room_accommodation_rules_id' => Str::uuid(),
                        'room_type_id' => $roomType->room_types_id,
                        'accommodation_type_id' => $accommodationType->accommodation_types_id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
        }

        DB::table('room_accommodation_rules')->insert($rules);
    }
};