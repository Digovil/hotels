<?php

use Illuminate\Support\Facades\Route;

Route::get('room_types/{id}', [App\Http\Controllers\API\V1\RoomTypes\RoomTypesController::class, 'show'])->name('api.v1.room_types.show');
Route::get('room_types', [App\Http\Controllers\API\V1\RoomTypes\RoomTypesController::class, 'index'])->name('api.v1.room_types.index');
Route::post('room_types/create', [App\Http\Controllers\API\V1\RoomTypes\RoomTypesController::class, 'store'])->name('api.v1.room_types.store');
Route::put('room_types/update/{id}', [App\Http\Controllers\API\V1\RoomTypes\RoomTypesController::class, 'update'])->name('api.v1.room_types.update');
Route::delete('room_types/delete/{id}', [App\Http\Controllers\API\V1\RoomTypes\RoomTypesController::class, 'destroy'])->name('api.v1.room_types.destroy');

    