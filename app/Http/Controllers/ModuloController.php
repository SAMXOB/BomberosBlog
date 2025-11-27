<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Modulo;
use Illuminate\Http\Request;

class ModuloController extends Controller
{
    /**
     * Mostrar módulos de un curso (vista admin)
     */
    public function index(Curso $curso)
    {
        $modulos = $curso->modulos()->withCount('lecciones')->get();
        return view('admin.modulos.index', compact('curso', 'modulos'));
    }

    /**
     * Formulario para crear módulo
     */
    public function create(Curso $curso)
    {
        return view('admin.modulos.create', compact('curso'));
    }

    /**
     * Guardar nuevo módulo
     */
    public function store(Request $request, Curso $curso)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'orden' => 'nullable|integer|min:0',
            'publicado' => 'boolean',
        ]);

        $validated['curso_id'] = $curso->id;
        $validated['orden'] = $validated['orden'] ?? ($curso->modulos()->max('orden') + 1);

        Modulo::create($validated);

        return redirect()->route('admin.cursos.modulos.index', $curso)
            ->with('success', 'Módulo creado exitosamente');
    }

    /**
     * Formulario para editar módulo
     */
    public function edit(Curso $curso, Modulo $modulo)
    {
        return view('admin.modulos.edit', compact('curso', 'modulo'));
    }

    /**
     * Actualizar módulo
     */
    public function update(Request $request, Curso $curso, Modulo $modulo)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'orden' => 'nullable|integer|min:0',
            'publicado' => 'boolean',
        ]);

        $modulo->update($validated);

        return redirect()->route('admin.cursos.modulos.index', $curso)
            ->with('success', 'Módulo actualizado exitosamente');
    }

    /**
     * Eliminar módulo
     */
    public function destroy(Curso $curso, Modulo $modulo)
    {
        $modulo->delete();

        return redirect()->route('admin.cursos.modulos.index', $curso)
            ->with('success', 'Módulo eliminado exitosamente');
    }

    /**
     * Reordenar módulos
     */
    public function reorder(Request $request, Curso $curso)
    {
        $validated = $request->validate([
            'modulos' => 'required|array',
            'modulos.*.id' => 'required|exists:modulos,id',
            'modulos.*.orden' => 'required|integer|min:0',
        ]);

        foreach ($validated['modulos'] as $moduloData) {
            Modulo::where('id', $moduloData['id'])
                ->update(['orden' => $moduloData['orden']]);
        }

        return response()->json(['success' => true]);
    }
}
