<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;

class CursoInscripcionController extends Controller
{
    /**
     * Mostrar cursos disponibles para inscripción
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoria = $request->input('categoria');

        $query = Curso::activos()->with(['user', 'inscritos']);

        if ($search) {
            $query->buscar($search);
        }

        if ($categoria) {
            $query->categoria($categoria);
        }

        $cursos = $query->paginate(12);
        $categorias = Curso::distinct()->pluck('categoria');

        return view('cursos.disponibles', compact('cursos', 'categorias', 'search', 'categoria'));
    }

    /**
     * Mostrar cursos en los que está inscrito el usuario
     */
    public function misCursos()
    {
        $cursosInscritos = auth()->user()
            ->cursosInscritos()
            ->withPivot('progreso', 'estado', 'inscrito_at')
            ->with('user')
            ->paginate(12);

        return view('cursos.mis-cursos', compact('cursosInscritos'));
    }

    /**
     * Inscribir al usuario en un curso
     */
    public function inscribir(Curso $curso)
    {
        $user = auth()->user();

        // Verificar si ya está inscrito
        if ($user->estaInscritoEn($curso->id)) {
            return back()->with('warning', 'Ya estás inscrito en este curso');
        }

        // Verificar que el curso esté activo
        if ($curso->estado !== 'activo') {
            return back()->with('error', 'Este curso no está disponible');
        }

        // Inscribir al usuario
        $user->cursosInscritos()->attach($curso->id, [
            'inscrito_at' => now(),
            'progreso' => 0,
            'estado' => 'activo'
        ]);

        return redirect()->route('cursos.mis-cursos')
            ->with('success', '¡Te has inscrito exitosamente en el curso!');
    }

    /**
     * Desinscribir al usuario de un curso
     */
    public function desinscribir(Curso $curso)
    {
        $user = auth()->user();

        if (!$user->estaInscritoEn($curso->id)) {
            return back()->with('error', 'No estás inscrito en este curso');
        }

        $user->cursosInscritos()->detach($curso->id);

        return back()->with('success', 'Te has desinscrito del curso');
    }

    /**
     * Ver detalle de un curso
     */
    public function show(Curso $curso)
    {
        $curso->load(['user', 'inscritos', 'modulosPublicados.leccionesPublicadas']);
        $estaInscrito = auth()->check() && auth()->user()->estaInscritoEn($curso->id);
        $totalInscritos = $curso->inscritos->count();
        $totalLecciones = $curso->totalLecciones();
        $duracionTotal = $curso->duracionTotal();

        // Si está inscrito, calcular su progreso
        $progreso = 0;
        if ($estaInscrito) {
            $progreso = auth()->user()->calcularProgresoCurso($curso->id);
        }

        return view('cursos.show', compact('curso', 'estaInscrito', 'totalInscritos', 'totalLecciones', 'duracionTotal', 'progreso'));
    }

    /**
     * Actualizar progreso del usuario en un curso
     */
    public function actualizarProgreso(Request $request, Curso $curso)
    {
        $user = auth()->user();

        if (!$user->estaInscritoEn($curso->id)) {
            return response()->json(['error' => 'No estás inscrito en este curso'], 403);
        }

        $validated = $request->validate([
            'progreso' => 'required|integer|min:0|max:100'
        ]);

        $user->cursosInscritos()->updateExistingPivot($curso->id, [
            'progreso' => $validated['progreso'],
            'completado_at' => $validated['progreso'] == 100 ? now() : null,
            'estado' => $validated['progreso'] == 100 ? 'completado' : 'activo'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Progreso actualizado'
        ]);
    }
}
