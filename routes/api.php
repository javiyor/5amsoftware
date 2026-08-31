<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    // Endpoint de verificación de licencias (se implementa en la Fase 8).
    Route::post('license/verify', function (): never {
        abort(501);
    })->middleware('throttle:api')->name('api.v1.license.verify');
});
