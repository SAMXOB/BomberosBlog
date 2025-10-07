<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CursoController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('cursos', CursoController::class);
