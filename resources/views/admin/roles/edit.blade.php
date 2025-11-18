@extends('admin.layout')

@section('title', 'Editar Rol')

@section('content')
<a href="{{ route('admin.roles.index') }}" style="color: #667eea; text-decoration: none; margin-bottom: 20px; display: inline-block;">← Volver a Roles</a>

<div style="max-width: 600px;">
    <h2 style="margin-bottom: 30px;">Editar Rol: {{ $role->name }}</h2>

    @if ($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.roles.update', $role) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nombre del Rol *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $role->name) }}" required>
            @error('name') <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Permisos *</label>
            <div style="max-height: 300px; overflow-y: auto; border: 1px solid #dee2e6; padding: 10px; border-radius: 5px;">
                @foreach ($permissions as $permission)
                    <div style="margin-bottom: 10px;">
                        <input type="checkbox" id="perm_{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}" {{ in_array($permission->name, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                        <label for="perm_{{ $permission->id }}" style="display: inline; margin: 0; font-weight: normal;">{{ $permission->name }}</label>
                    </div>
                @endforeach
            </div>
            @error('permissions') <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span> @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Actualizar Rol</button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
