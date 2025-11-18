@extends('admin.layout')

@section('title', 'Editar Curso')

@section('content')
<a href="{{ route('admin.cursos.index') }}" style="color: #667eea; text-decoration: none; margin-bottom: 20px; display: inline-block;">← Volver a Cursos</a>

<div style="max-width: 600px;">
    <h2 style="margin-bottom: 30px;">Editar Curso: {{ $curso->titulo }}</h2>

    @if ($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.cursos.update', $curso) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="titulo">Título *</label>
            <input type="text" id="titulo" name="titulo" value="{{ old('titulo', $curso->titulo) }}" required>
            @error('titulo') <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción *</label>
            <textarea id="descripcion" name="descripcion" required>{{ old('descripcion', $curso->descripcion) }}</textarea>
            @error('descripcion') <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="categoria">Categoría *</label>
            <input type="text" id="categoria" name="categoria" value="{{ old('categoria', $curso->categoria) }}" required>
            @error('categoria') <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="estado">Estado *</label>
            <select id="estado" name="estado" required>
                <option value="activo" {{ old('estado', $curso->estado) === 'activo' ? 'selected' : '' }}>Activo</option>
                <option value="inactivo" {{ old('estado', $curso->estado) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                <option value="draft" {{ old('estado', $curso->estado) === 'draft' ? 'selected' : '' }}>Borrador</option>
            </select>
            @error('estado') <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span> @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Actualizar Curso</button>
            <a href="{{ route('admin.cursos.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
