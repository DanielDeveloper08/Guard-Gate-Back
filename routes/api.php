<?php

use Illuminate\Support\Facades\Route;

Route::prefix('autenticacion')->group(function () {
    Route::post('login', 'App\Http\Controllers\AutenticacionController@login');
    Route::post('register', 'App\Http\Controllers\AutenticacionController@register');
    Route::post('recuperarContrasena', 'App\Http\Controllers\AutenticacionController@sendEmailResetPassword');
    Route::post('validarOtp', 'App\Http\Controllers\AutenticacionController@validateOtp');
    Route::post('cambiarContrasena', 'App\Http\Controllers\AutenticacionController@resetPassword');
});
