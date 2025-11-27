<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Mostrar perfil del usuario
     */
    public function show()
    {
        $user = auth()->user()->load(['roles', 'cursosInscritos', 'cursosCreados']);

        return view('profile.show', compact('user'));
    }

    /**
     * Formulario para editar perfil
     */
    public function edit()
    {
        $user = auth()->user();

        return view('profile.edit', compact('user'));
    }

    /**
     * Actualizar información del perfil
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Perfil actualizado exitosamente');
    }

    /**
     * Cambiar contraseña
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = auth()->user();

        // Verificar contraseña actual
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'La contraseña actual no es correcta'
            ]);
        }

        // Actualizar contraseña
        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return back()->with('success', 'Contraseña actualizada exitosamente');
    }

    /**
     * Estadísticas del usuario
     */
    public function estadisticas()
    {
        $user = auth()->user();

        $stats = [
            'cursosInscritos' => $user->cursosInscritos()->count(),
            'cursosCompletados' => $user->cursosInscritos()
                ->wherePivot('estado', 'completado')
                ->count(),
            'cursosActivos' => $user->cursosInscritos()
                ->wherePivot('estado', 'activo')
                ->count(),
            'cursosCreados' => $user->cursosCreados()->count(),
            'progresoPromedio' => $user->cursosInscritos()
                ->avg('curso_user.progreso') ?? 0,
        ];

        return view('profile.estadisticas', compact('stats'));
    }
}
