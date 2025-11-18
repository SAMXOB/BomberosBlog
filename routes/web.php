<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;

// Rutas públicas
Route::get('/', function () {
    // Si está autenticado, ir al admin
    if (auth()->check()) {
        return redirect('/admin');
    }
    return view('welcome');
});

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Rutas autenticadas
Route::middleware('auth')->group(function () {
    // Dashboard de admin
    Route::get('/admin', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    // Gestión de Usuarios
    Route::prefix('admin/usuarios')->name('admin.users.')->group(function () {
        Route::get('/', [AdminController::class, 'indexUsers'])
            ->name('index');
        Route::get('/crear', [AdminController::class, 'createUser'])
            ->name('create');
        Route::post('/', [AdminController::class, 'storeUser'])
            ->name('store');
        Route::get('/{user}/editar', [AdminController::class, 'editUser'])
            ->name('edit');
        Route::put('/{user}', [AdminController::class, 'updateUser'])
            ->name('update');
        Route::delete('/{user}', [AdminController::class, 'destroyUser'])
            ->name('destroy');
    });

    // Gestión de Cursos
    Route::prefix('admin/cursos')->name('admin.cursos.')->group(function () {
        Route::get('/', [AdminController::class, 'indexCursos'])
            ->name('index');
        Route::get('/crear', [AdminController::class, 'createCurso'])
            ->name('create');
        Route::post('/', [AdminController::class, 'storeCurso'])
            ->name('store');
        Route::get('/{curso}/editar', [AdminController::class, 'editCurso'])
            ->name('edit');
        Route::put('/{curso}', [AdminController::class, 'updateCurso'])
            ->name('update');
        Route::delete('/{curso}', [AdminController::class, 'destroyCurso'])
            ->name('destroy');
    });

    // Gestión de Roles y Permisos
    Route::prefix('admin/roles')->name('admin.roles.')->group(function () {
        Route::get('/', [AdminController::class, 'indexRoles'])
            ->name('index');
        Route::get('/crear', [AdminController::class, 'createRole'])
            ->name('create');
        Route::post('/', [AdminController::class, 'storeRole'])
            ->name('store');
        Route::get('/{role}/editar', [AdminController::class, 'editRole'])
            ->name('edit');
        Route::put('/{role}', [AdminController::class, 'updateRole'])
            ->name('update');
        Route::delete('/{role}', [AdminController::class, 'destroyRole'])
            ->name('destroy');
    });
});

// Rutas de cursos públicas
Route::resource('cursos', CursoController::class);

