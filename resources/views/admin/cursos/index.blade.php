@extends('admin.layout')

@section('title', 'Gestión de Cursos')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Cursos</h2>
    @can('create_cursos')
    <a href="{{ route('admin.cursos.create') }}" class="btn btn-primary">+ Nuevo Curso</a>
    @endcan
</div>

<table>
    <thead>
        <tr>
            <th>Título</th>
            <th>Categoría</th>
            <th>Estado</th>
            <th>Autor</th>
            <th>Creado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($cursos as $curso)
        <tr>
            <td>{{ $curso->titulo }}</td>
            <td>{{ $curso->categoria }}</td>
            <td>
                @if ($curso->estado === 'activo')
                    <span class="badge badge-success">{{ ucfirst($curso->estado) }}</span>
                @elseif ($curso->estado === 'inactivo')
                    <span class="badge badge-danger">{{ ucfirst($curso->estado) }}</span>
                @else
                    <span class="badge badge-primary">{{ ucfirst($curso->estado) }}</span>
                @endif
            </td>
            <td>{{ $curso->user->name ?? 'N/A' }}</td>
            <td>{{ $curso->created_at->format('d/m/Y') }}</td>
            <td>
                <div class="actions">
                    <a href="{{ route('admin.cursos.modulos.index', $curso) }}" class="btn btn-sm btn-info" title="Gestionar Módulos">
                        <i class="bi bi-folder2-open"></i> Módulos
                    </a>
                    @can('edit_cursos')
                    <a href="{{ route('admin.cursos.edit', $curso) }}" class="edit">Editar</a>
                    @endcan
                    @can('delete_cursos')
                    <form method="POST" action="{{ route('admin.cursos.destroy', $curso) }}" style="display: inline;" onsubmit="return confirm('¿Estás seguro?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete">Eliminar</button>
                    </form>
                    @endcan
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align: center; color: #999;">No hay cursos registrados</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 20px;">
    {{ $cursos->links() }}
</div>
@endsection
