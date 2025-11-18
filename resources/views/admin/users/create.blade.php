@extends('admin.layout')

@section('title', 'Crear Nuevo Usuario')

@section('content')
<a href="{{ route('admin.users.index') }}" style="color: #667eea; text-decoration: none; margin-bottom: 20px; display: inline-block;">← Volver a Usuarios</a>

<div style="max-width: 500px;">
    <h2 style="margin-bottom: 30px;">Crear Nuevo Usuario</h2>

    @if ($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div class="form-group">
            <label for="name">Nombre *</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name') <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="email">Correo Electrónico *</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            @error('email') <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="password">Contraseña *</label>
            <input type="password" id="password" name="password" required>
            @error('password') <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmar Contraseña *</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <div class="form-group">
            <label>Roles *</label>
            @foreach ($roles as $role)
                <div style="margin-bottom: 10px;">
                    <input type="checkbox" id="role_{{ $role->id }}" name="roles[]" value="{{ $role->name }}" {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
                    <label for="role_{{ $role->id }}" style="display: inline; margin: 0; font-weight: normal;">{{ $role->name }}</label>
                </div>
            @endforeach
            @error('roles') <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span> @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Crear Usuario</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
