
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Lista de Cursos</h1>

    <a href="{{ route('cursos.create') }}" class="btn btn-primary mb-3">Crear Curso</a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Título</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cursos as $curso)
                <tr>
                    <td>{{ $curso->titulo }}</td>
                    <td>{{ $curso->categoria }}</td>
                    <td>
                        <a href="{{ route('cursos.edit', $curso->id) }}" class="btn btn-warning btn-sm">Editar</a>

                        <form action="{{ route('cursos.destroy', $curso->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este curso?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">No hay cursos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

