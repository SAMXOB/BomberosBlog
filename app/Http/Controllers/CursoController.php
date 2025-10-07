<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index()
    {
        $cursos = Curso::all();
        return view('cursos.index', compact('cursos'));
    }

    public function create()
    {
        return view('cursos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required',
            'descripcion' => 'required',
            'categoria' => 'required'
        ]);

        Curso::create($request->all());
        return redirect()->route('cursos.index')->with('success', 'Curso creado correctamente');
    }

    public function edit(Curso $curso)
    {
        return view('cursos.edit', compact('curso'));
    }

    public function update(Request $request, Curso $curso)
    {
        $request->validate([
            'titulo' => 'required',
            'descripcion' => 'required',
            'categoria' => 'required'
        ]);

        $curso->update($request->all());
        return redirect()->route('cursos.index')->with('success', 'Curso actualizado correctamente');
    }

    public function destroy(Curso $curso)
    {
        $curso->delete();
        return redirect()->route('cursos.index')->with('success', 'Curso eliminado correctamente');
    }
}
