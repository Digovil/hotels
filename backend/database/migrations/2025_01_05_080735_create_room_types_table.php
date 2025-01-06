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
        Schema::create('room_types', function (Blueprint $table) {
            $table->uuid('room_types_id')->primary();
            $table->string('name', 50)->unique();
            $table->timestamp('created_at')->default(now());
            $table->timestamp('updated_at')->default(now());
        });

        $this->insertDefaultRecords();
    }

    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }

    private function insertDefaultRecords(): void
    {
        DB::table('room_types')->insert([
            [
                'room_types_id' => Str::uuid(),
                'name' => 'ESTANDAR',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'room_types_id' => Str::uuid(),
                'name' => 'JUNIOR',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'room_types_id' => Str::uuid(),
                'name' => 'SUITE',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
};