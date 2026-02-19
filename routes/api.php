<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimeController;

Route::apiResource('times', TimeController::class);