@extends('admin.layout')

@section('title', 'Gestión de Usuarios')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Usuarios</h2>
    @can('create_users')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Nuevo Usuario</a>
    @endcan
</div>

<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Roles</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                @forelse ($user->roles as $role)
                    <span class="badge badge-primary">{{ $role->name }}</span>
                @empty
                    <span style="color: #999;">Sin rol</span>
                @endforelse
            </td>
            <td>
                <div class="actions">
                    @can('edit_users')
                    <a href="{{ route('admin.users.edit', $user) }}" class="edit">Editar</a>
                    @endcan
                    @can('delete_users')
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;" onsubmit="return confirm('¿Estás seguro?');">
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
            <td colspan="4" style="text-align: center; color: #999;">No hay usuarios registrados</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 20px;">
    {{ $users->links() }}
</div>
@endsection
