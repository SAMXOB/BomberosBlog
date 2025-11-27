<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CursoInscripcionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\LeccionController;

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

    // Perfil de usuario
    Route::prefix('perfil')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/editar', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/actualizar', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        Route::get('/estadisticas', [ProfileController::class, 'estadisticas'])->name('estadisticas');
    });

    // Mis cursos e inscripciones
    Route::prefix('mis-cursos')->name('cursos.')->group(function () {
        Route::get('/', [CursoInscripcionController::class, 'misCursos'])->name('mis-cursos');
        Route::get('/disponibles', [CursoInscripcionController::class, 'index'])->name('disponibles');
        Route::post('/{curso}/inscribir', [CursoInscripcionController::class, 'inscribir'])->name('inscribir');
        Route::delete('/{curso}/desinscribir', [CursoInscripcionController::class, 'desinscribir'])->name('desinscribir');
        Route::put('/{curso}/progreso', [CursoInscripcionController::class, 'actualizarProgreso'])->name('progreso');
    });

    // Ver detalle de curso
    Route::get('/cursos/{curso}', [CursoInscripcionController::class, 'show'])->name('cursos.show');

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

        // Gestión de Módulos
        Route::prefix('{curso}/modulos')->name('modulos.')->group(function () {
            Route::get('/', [ModuloController::class, 'index'])->name('index');
            Route::get('/crear', [ModuloController::class, 'create'])->name('create');
            Route::post('/', [ModuloController::class, 'store'])->name('store');
            Route::get('/{modulo}/editar', [ModuloController::class, 'edit'])->name('edit');
            Route::put('/{modulo}', [ModuloController::class, 'update'])->name('update');
            Route::delete('/{modulo}', [ModuloController::class, 'destroy'])->name('destroy');
            Route::post('/reordenar', [ModuloController::class, 'reorder'])->name('reorder');
        });
    });

    // Gestión de Lecciones
    Route::prefix('admin/modulos/{modulo}/lecciones')->name('admin.modulos.lecciones.')->group(function () {
        Route::get('/', [LeccionController::class, 'index'])->name('index');
        Route::get('/crear', [LeccionController::class, 'create'])->name('create');
        Route::post('/', [LeccionController::class, 'store'])->name('store');
        Route::get('/{leccion}/editar', [LeccionController::class, 'edit'])->name('edit');
        Route::put('/{leccion}', [LeccionController::class, 'update'])->name('update');
        Route::delete('/{leccion}', [LeccionController::class, 'destroy'])->name('destroy');
    });

    // Visualización y completado de lecciones
    Route::prefix('lecciones')->name('lecciones.')->group(function () {
        Route::get('/{leccion}', [LeccionController::class, 'show'])->name('show');
        Route::post('/{leccion}/completar', [LeccionController::class, 'completar'])->name('completar');
        Route::delete('/{leccion}/descompletar', [LeccionController::class, 'descompletar'])->name('descompletar');
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

// Rutas públicas de cursos
Route::get('/cursos', [CursoController::class, 'index'])->name('cursos.index');

