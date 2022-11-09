<?php

use Illuminate\Support\Facades\Route;
use SmurfWorks\SieveApp\Http\Controllers as Controllers;

Route::get('/', [Controllers\SieveController::class, 'index'])->name('sieve.index');
Route::post('/a/result', [Controllers\SieveController::class, 'result'])->name('sieve.result');
