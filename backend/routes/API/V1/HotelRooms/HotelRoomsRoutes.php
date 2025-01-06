<?php

use Illuminate\Support\Facades\Route;

Route::get('hotel_rooms/{id}', [App\Http\Controllers\API\V1\HotelRooms\HotelRoomsController::class, 'show'])->name('api.v1.hotel_rooms.show');
Route::get('hotel_rooms', [App\Http\Controllers\API\V1\HotelRooms\HotelRoomsController::class, 'index'])->name('api.v1.hotel_rooms.index');
Route::post('hotel_rooms/create', [App\Http\Controllers\API\V1\HotelRooms\HotelRoomsController::class, 'store'])->name('api.v1.hotel_rooms.store');
Route::put('hotel_rooms/update/{id}', [App\Http\Controllers\API\V1\HotelRooms\HotelRoomsController::class, 'update'])->name('api.v1.hotel_rooms.update');
Route::delete('hotel_rooms/delete/{id}', [App\Http\Controllers\API\V1\HotelRooms\HotelRoomsController::class, 'destroy'])->name('api.v1.hotel_rooms.destroy');

    