<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\Leccion;
use Illuminate\Http\Request;

class LeccionController extends Controller
{
    /**
     * Listar lecciones de un módulo
     */
    public function index(Modulo $modulo)
    {
        $lecciones = $modulo->lecciones;
        return view('admin.lecciones.index', compact('modulo', 'lecciones'));
    }

    /**
     * Formulario para crear lección
     */
    public function create(Modulo $modulo)
    {
        return view('admin.lecciones.create', compact('modulo'));
    }

    /**
     * Guardar nueva lección
     */
    public function store(Request $request, Modulo $modulo)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'tipo' => 'required|in:texto,video,archivo,quiz',
            'url_recurso' => 'nullable|string',
            'duracion_minutos' => 'nullable|integer|min:0',
            'orden' => 'nullable|integer|min:0',
            'es_gratis' => 'boolean',
            'publicado' => 'boolean',
        ]);

        $validated['modulo_id'] = $modulo->id;
        $validated['orden'] = $validated['orden'] ?? ($modulo->lecciones()->max('orden') + 1);

        Leccion::create($validated);

        return redirect()->route('admin.modulos.lecciones.index', $modulo)
            ->with('success', 'Lección creada exitosamente');
    }

    /**
     * Ver lección (estudiante)
     */
    public function show(Leccion $leccion)
    {
        $curso = $leccion->modulo->curso;
        $user = auth()->user();

        // Verificar si está inscrito
        if (!$user->estaInscritoEn($curso->id) && !$leccion->es_gratis) {
            return redirect()->route('cursos.show', $curso)
                ->with('error', 'Debes inscribirte en el curso para acceder a esta lección');
        }

        $completada = $user->completoLeccion($leccion->id);
        $modulo = $leccion->modulo->load('lecciones');

        return view('lecciones.show', compact('leccion', 'curso', 'modulo', 'completada'));
    }

    /**
     * Formulario para editar lección
     */
    public function edit(Modulo $modulo, Leccion $leccion)
    {
        return view('admin.lecciones.edit', compact('modulo', 'leccion'));
    }

    /**
     * Actualizar lección
     */
    public function update(Request $request, Modulo $modulo, Leccion $leccion)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'tipo' => 'required|in:texto,video,archivo,quiz',
            'url_recurso' => 'nullable|string',
            'duracion_minutos' => 'nullable|integer|min:0',
            'orden' => 'nullable|integer|min:0',
            'es_gratis' => 'boolean',
            'publicado' => 'boolean',
        ]);

        $leccion->update($validated);

        return redirect()->route('admin.modulos.lecciones.index', $modulo)
            ->with('success', 'Lección actualizada exitosamente');
    }

    /**
     * Eliminar lección
     */
    public function destroy(Modulo $modulo, Leccion $leccion)
    {
        $leccion->delete();

        return redirect()->route('admin.modulos.lecciones.index', $modulo)
            ->with('success', 'Lección eliminada exitosamente');
    }

    /**
     * Marcar lección como completada
     */
    public function completar(Leccion $leccion)
    {
        $user = auth()->user();
        $curso = $leccion->modulo->curso;

        // Verificar inscripción
        if (!$user->estaInscritoEn($curso->id)) {
            return response()->json(['error' => 'No estás inscrito en este curso'], 403);
        }

        // Marcar como completada
        if (!$user->completoLeccion($leccion->id)) {
            $user->leccionesCompletadas()->attach($leccion->id, [
                'completado_at' => now(),
                'tiempo_visto' => request('tiempo_visto', 0)
            ]);

            // Actualizar progreso del curso
            $progreso = $user->calcularProgresoCurso($curso->id);
            $user->cursosInscritos()->updateExistingPivot($curso->id, [
                'progreso' => $progreso,
                'completado_at' => $progreso == 100 ? now() : null,
                'estado' => $progreso == 100 ? 'completado' : 'activo'
            ]);

            return response()->json([
                'success' => true,
                'progreso' => $progreso,
                'message' => 'Lección completada'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ya habías completado esta lección'
        ]);
    }

    /**
     * Desmarcar lección como completada
     */
    public function descompletar(Leccion $leccion)
    {
        $user = auth()->user();
        $curso = $leccion->modulo->curso;

        if ($user->completoLeccion($leccion->id)) {
            $user->leccionesCompletadas()->detach($leccion->id);

            // Recalcular progreso
            $progreso = $user->calcularProgresoCurso($curso->id);
            $user->cursosInscritos()->updateExistingPivot($curso->id, [
                'progreso' => $progreso,
                'estado' => 'activo'
            ]);

            return response()->json([
                'success' => true,
                'progreso' => $progreso
            ]);
        }

        return response()->json(['error' => 'La lección no estaba completada'], 400);
    }
}
