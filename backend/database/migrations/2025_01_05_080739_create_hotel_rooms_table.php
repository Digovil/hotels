<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotel_rooms', function (Blueprint $table) {
            $table->uuid('hotel_rooms_id')->primary();
            $table->foreignUuid('hotel_id')->references('hotels_id')->on('hotels');
            $table->foreignUuid('room_type_id')->references('room_types_id')->on('room_types');
            $table->foreignUuid('accommodation_type_id')->references('accommodation_types_id')->on('accommodation_types');
            $table->integer('quantity');
            $table->timestamp('created_at')->default(now());
            $table->timestamp('updated_at')->default(now());

        });

        $this->addCheckConstraints();
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_rooms');
    }

    private function addCheckConstraints(): void
    {
        DB::statement('ALTER TABLE hotel_rooms ADD CONSTRAINT check_quantity_positive CHECK (quantity > 0)');
    }
};