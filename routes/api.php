<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimeController;
use App\Http\Controllers\CampeonatoController;

Route::apiResource('times', TimeController::class);
Route::apiResource('campeonatos', CampeonatoController::class);