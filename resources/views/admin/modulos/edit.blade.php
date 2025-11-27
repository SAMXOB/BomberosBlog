@extends('admin.layout')

@section('title', 'Editar Módulo')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>✏️ Editar Módulo</h2>
            <a href="{{ route('admin.cursos.modulos.index', $curso) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

        <div class="card shadow">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Editando: {{ $modulo->titulo }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.cursos.modulos.update', [$curso, $modulo]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título del Módulo <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('titulo') is-invalid @enderror"
                               id="titulo"
                               name="titulo"
                               value="{{ old('titulo', $modulo->titulo) }}"
                               required>
                        @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror"
                                  id="descripcion"
                                  name="descripcion"
                                  rows="3">{{ old('descripcion', $modulo->descripcion) }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="orden" class="form-label">Orden</label>
                            <input type="number"
                                   class="form-control @error('orden') is-invalid @enderror"
                                   id="orden"
                                   name="orden"
                                   value="{{ old('orden', $modulo->orden) }}"
                                   min="0">
                            @error('orden')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="publicado" class="form-label">Estado</label>
                            <select class="form-select @error('publicado') is-invalid @enderror"
                                    id="publicado"
                                    name="publicado">
                                <option value="1" {{ old('publicado', $modulo->publicado) == '1' ? 'selected' : '' }}>Publicado</option>
                                <option value="0" {{ old('publicado', $modulo->publicado) == '0' ? 'selected' : '' }}>Borrador</option>
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
                            <i class="bi bi-check-circle"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Enlace rápido a lecciones -->
        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Gestionar Lecciones de este Módulo</h6>
                        <small class="text-muted">{{ $modulo->lecciones()->count() }} lecciones</small>
                    </div>
                    <a href="{{ route('admin.modulos.lecciones.index', $modulo) }}" class="btn btn-primary">
                        <i class="bi bi-list-ul"></i> Ver Lecciones
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
