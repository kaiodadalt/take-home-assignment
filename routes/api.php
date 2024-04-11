<?php

use App\Http\Controllers\BankingController;
use Illuminate\Support\Facades\Route;

Route::post('/reset', [BankingController::class, 'reset']);

Route::get('/balance', [BankingController::class, 'getBalance']);
Route::post('/event', [BankingController::class, 'handleEvent']);