<?php

use Illuminate\Support\Facades\Route;

Route::get('room_accommodation_rules/{id}', [App\Http\Controllers\API\V1\RoomAccommodationRules\RoomAccommodationRulesController::class, 'show'])->name('api.v1.room_accommodation_rules.show');
Route::get('room_accommodation_rules', [App\Http\Controllers\API\V1\RoomAccommodationRules\RoomAccommodationRulesController::class, 'index'])->name('api.v1.room_accommodation_rules.index');
Route::post('room_accommodation_rules/create', [App\Http\Controllers\API\V1\RoomAccommodationRules\RoomAccommodationRulesController::class, 'store'])->name('api.v1.room_accommodation_rules.store');
Route::put('room_accommodation_rules/update/{id}', [App\Http\Controllers\API\V1\RoomAccommodationRules\RoomAccommodationRulesController::class, 'update'])->name('api.v1.room_accommodation_rules.update');
Route::delete('room_accommodation_rules/delete/{id}', [App\Http\Controllers\API\V1\RoomAccommodationRules\RoomAccommodationRulesController::class, 'destroy'])->name('api.v1.room_accommodation_rules.destroy');

    