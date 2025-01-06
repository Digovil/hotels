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
        Schema::create('accommodation_types', function (Blueprint $table) {
            $table->uuid('accommodation_types_id')->primary();
            $table->string('name', 50)->unique();
            $table->timestamp('created_at')->default(now());
            $table->timestamp('updated_at')->default(now());
        });

        $this->insertDefaultAccommodationTypes();
    }

    public function down(): void
    {
        Schema::dropIfExists('accommodation_types');
    }

    private function insertDefaultAccommodationTypes(): void
    {
        DB::table('accommodation_types')->insert([
            [
                'accommodation_types_id' => Str::uuid(),
                'name' => 'SENCILLA',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'accommodation_types_id' => Str::uuid(),
                'name' => 'DOBLE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'accommodation_types_id' => Str::uuid(),
                'name' => 'TRIPLE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'accommodation_types_id' => Str::uuid(),
                'name' => 'CUADRUPLE',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
};