<?php

use Illuminate\Support\Facades\Route;

Route::get('hotels/{id}', [App\Http\Controllers\API\V1\Hotels\HotelsController::class, 'show'])->name('api.v1.hotels.show');
Route::get('hotels', [App\Http\Controllers\API\V1\Hotels\HotelsController::class, 'index'])->name('api.v1.hotels.index');
Route::post('hotels/create', [App\Http\Controllers\API\V1\Hotels\HotelsController::class, 'store'])->name('api.v1.hotels.store');
Route::put('hotels/update/{id}', [App\Http\Controllers\API\V1\Hotels\HotelsController::class, 'update'])->name('api.v1.hotels.update');
Route::delete('hotels/delete/{id}', [App\Http\Controllers\API\V1\Hotels\HotelsController::class, 'destroy'])->name('api.v1.hotels.destroy');

    