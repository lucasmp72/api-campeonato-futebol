<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimeController;
use App\Http\Controllers\CampeonatoController;
use App\Http\Controllers\CampeonatoTimeController;

Route::apiResource('times', TimeController::class);
Route::apiResource('campeonatos', CampeonatoController::class);
Route::apiResource('campeonatos-times', CampeonatoTimeController::class);

Route::prefix('campeonatos')->group(function () {

    Route::post(
        'simular-campeonato/{id}',
        [CampeonatoController::class, 'simularCampeonato']
    );

    Route::get(
        'resultados-campeonato/{id}',
        [CampeonatoController::class, 'resultadosCampeonato']
    );

});