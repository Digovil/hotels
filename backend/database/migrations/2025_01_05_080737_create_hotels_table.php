<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->uuid('hotels_id')->primary();
            $table->string('name', 100)->unique();
            $table->string('address', 200);
            $table->string('city', 100);
            $table->string('tax_id', 20)->unique();
            $table->integer('total_rooms');
            $table->timestamp('created_at')->default(now());
            $table->timestamp('updated_at')->default(now());
        });

        $this->addCheckConstraints();
    }

    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }

    private function addCheckConstraints(): void
    {
        DB::statement('ALTER TABLE hotels ADD CONSTRAINT check_total_rooms_positive CHECK (total_rooms > 0)');
    }
};