@extends('admin.layout')

@section('title', 'Gestión de Roles')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Roles y Permisos</h2>
    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">+ Nuevo Rol</a>
</div>

<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Permisos</th>
            <th>Usuarios</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($roles as $role)
        <tr>
            <td>{{ $role->name }}</td>
            <td>
                <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                    @forelse ($role->permissions as $permission)
                        <span class="badge badge-primary">{{ $permission->name }}</span>
                    @empty
                        <span style="color: #999;">Sin permisos</span>
                    @endforelse
                </div>
            </td>
            <td>{{ $role->users_count ?? 0 }}</td>
            <td>
                <div class="actions">
                    <a href="{{ route('admin.roles.edit', $role) }}" class="edit">Editar</a>
                    <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" style="display: inline;" onsubmit="return confirm('¿Estás seguro?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete">Eliminar</button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" style="text-align: center; color: #999;">No hay roles registrados</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 20px;">
    {{ $roles->links() }}
</div>
@endsection
