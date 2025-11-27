@extends('admin.layout')

@section('title', 'Crear Módulo')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>➕ Crear Nuevo Módulo</h2>
            <a href="{{ route('admin.cursos.modulos.index', $curso) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Curso: {{ $curso->titulo }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.cursos.modulos.store', $curso) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título del Módulo <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('titulo') is-invalid @enderror"
                               id="titulo"
                               name="titulo"
                               value="{{ old('titulo') }}"
                               required
                               placeholder="Ej: Introducción a los primeros auxilios">
                        @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror"
                                  id="descripcion"
                                  name="descripcion"
                                  rows="3"
                                  placeholder="Breve descripción del contenido del módulo">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Opcional. Ayuda a los estudiantes a entender el contenido.</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="orden" class="form-label">Orden</label>
                            <input type="number"
                                   class="form-control @error('orden') is-invalid @enderror"
                                   id="orden"
                                   name="orden"
                                   value="{{ old('orden', 1) }}"
                                   min="0">
                            @error('orden')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Deja en blanco para agregar al final.</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="publicado" class="form-label">Estado</label>
                            <select class="form-select @error('publicado') is-invalid @enderror"
                                    id="publicado"
                                    name="publicado">
                                <option value="1" {{ old('publicado', '1') == '1' ? 'selected' : '' }}>Publicado</option>
                                <option value="0" {{ old('publicado') == '0' ? 'selected' : '' }}>Borrador</option>
                            </select>
                            @error('publicado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.cursos.modulos.index', $curso) }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Crear Módulo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
