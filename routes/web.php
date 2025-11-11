<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\RoleController;

Route::resource('cursos', CursoController::class);
Route::resource('roles', RoleController::class);
Route::get('/', function () {
    return view('welcome');
});


