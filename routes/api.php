<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;

Route::get('/consultations', [RegistrationController::class, 'apiIndex']);
Route::post('/register', [RegistrationController::class, 'apiStore']);