<?php

use Illuminate\Support\Facades\Route;

Route::get('accommodation_types/{id}', [App\Http\Controllers\API\V1\AccommodationTypes\AccommodationTypesController::class, 'show'])->name('api.v1.accommodation_types.show');
Route::get('accommodation_types', [App\Http\Controllers\API\V1\AccommodationTypes\AccommodationTypesController::class, 'index'])->name('api.v1.accommodation_types.index');
Route::post('accommodation_types/create', [App\Http\Controllers\API\V1\AccommodationTypes\AccommodationTypesController::class, 'store'])->name('api.v1.accommodation_types.store');
Route::put('accommodation_types/update/{id}', [App\Http\Controllers\API\V1\AccommodationTypes\AccommodationTypesController::class, 'update'])->name('api.v1.accommodation_types.update');
Route::delete('accommodation_types/delete/{id}', [App\Http\Controllers\API\V1\AccommodationTypes\AccommodationTypesController::class, 'destroy'])->name('api.v1.accommodation_types.destroy');

    