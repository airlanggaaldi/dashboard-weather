<?php

use App\Http\Controllers\Api\DataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/data', [DataController::class, 'getData']);
Route::get('/area', [DataController::class, 'getArea']);
Route::get('/year-average', [DataController::class, 'getYearAvg']);
Route::get('/year-extrem', [DataController::class, 'getYearExtrem']);
Route::get('/summary', [DataController::class, 'getSummary']);
