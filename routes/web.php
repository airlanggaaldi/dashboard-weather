<?php

use App\Http\Controllers\DataController;
use Illuminate\Support\Facades\Route;

Route::get('/data', [DataController::class, 'averageTemperature']);
Route::get('/', function () {
    return view('welcome');
});

