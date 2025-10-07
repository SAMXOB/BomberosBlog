<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CursoController;
Route::resource('cursos', CursoController::class);
Route::get('/', function () {
    return view('welcome');
});


