<?php

use App\Http\Controllers\NotifyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/notify', [NotifyController::class, 'store'])->name('notify');
