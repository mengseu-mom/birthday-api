<?php

use App\Http\Controllers\BirthdayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/birthdays', [BirthdayController::class, 'store']);
Route::get('/birthdays/token/{token}', [BirthdayController::class, 'showByToken']);
Route::get('/image/{path}', [BirthdayController::class, 'image'])
    ->where('path', '.*');
