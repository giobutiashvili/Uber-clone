<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Logincontroller;

Route::post('/login', [LoginController::class, 'submit']);