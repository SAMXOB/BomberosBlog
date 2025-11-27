@extends('admin.layout')

@section('title', 'Editar Lección')

@section('content')
<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>✏️ Editar Lección</h2>
            <a href="{{ route('admin.modulos.lecciones.index', $modulo) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

        <div class="card shadow">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Editando: {{ $leccion->titulo }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.modulos.lecciones.update', [$modulo, $leccion]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="titulo" class="form-label">Título de la Lección <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('titulo') is-invalid @enderror"
                                   id="titulo"
                                   name="titulo"
                                   value="{{ old('titulo', $leccion->titulo) }}"
                                   required>
                            @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="tipo" class="form-label">Tipo de Contenido <span class="text-danger">*</span></label>
                            <select class="form-select @error('tipo') is-invalid @enderror"
                                    id="tipo"
                                    name="tipo"
                                    required>
                                <option value="texto" {{ old('tipo', $leccion->tipo) === 'texto' ? 'selected' : '' }}>📝 Texto/Lectura</option>
                                <option value="video" {{ old('tipo', $leccion->tipo) === 'video' ? 'selected' : '' }}>🎥 Video</option>
                                <option value="archivo" {{ old('tipo', $leccion->tipo) === 'archivo' ? 'selected' : '' }}>📎 Archivo</option>
                                <option value="quiz" {{ old('tipo', $leccion->tipo) === 'quiz' ? 'selected' : '' }}>❓ Quiz</option>
                            </select>
                            @error('tipo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="contenido" class="form-label">Contenido <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('contenido') is-invalid @enderror"
                                  id="contenido"
                                  name="contenido"
                                  rows="8"
                                  required>{{ old('contenido', $leccion->contenido) }}</textarea>
                        @error('contenido')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="url_recurso" class="form-label">URL del Recurso</label>
                        <input type="url"
                               class="form-control @error('url_recurso') is-invalid @enderror"
                               id="url_recurso"
                               name="url_recurso"
                               value="{{ old('url_recurso', $leccion->url_recurso) }}"
                               placeholder="https://youtube.com/watch?v=... o https://ejemplo.com/archivo.pdf">
                        @error('url_recurso')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Para videos: URL de YouTube o archivo de video.
                            Para archivos: URL del archivo descargable.
                        </small>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="duracion_minutos" class="form-label">Duración (minutos)</label>
                            <input type="number"
                                   class="form-control @error('duracion_minutos') is-invalid @enderror"
                                   id="duracion_minutos"
                                   name="duracion_minutos"
                                   value="{{ old('duracion_minutos', $leccion->duracion_minutos) }}"
                                   min="0">
                            @error('duracion_minutos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="orden" class="form-label">Orden</label>
                            <input type="number"
                                   class="form-control @error('orden') is-invalid @enderror"
                                   id="orden"
                                   name="orden"
                                   value="{{ old('orden', $leccion->orden) }}"
                                   min="0">
                            @error('orden')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="publicado" class="form-label">Estado</label>
                            <select class="form-select @error('publicado') is-invalid @enderror"
                                    id="publicado"
                                    name="publicado">
                                <option value="1" {{ old('publicado', $leccion->publicado) == '1' ? 'selected' : '' }}>Publicado</option>
                                <option value="0" {{ old('publicado', $leccion->publicado) == '0' ? 'selected' : '' }}>Borrador</option>
                            </select>
                            @error('publicado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="es_gratis" class="form-label">Acceso</label>
                            <select class="form-select @error('es_gratis') is-invalid @enderror"
                                    id="es_gratis"
                                    name="es_gratis">
                                <option value="0" {{ old('es_gratis', $leccion->es_gratis) == '0' ? 'selected' : '' }}>Solo inscritos</option>
                                <option value="1" {{ old('es_gratis', $leccion->es_gratis) == '1' ? 'selected' : '' }}>🎁 Gratis (Vista previa)</option>
                            </select>
                            @error('es_gratis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.modulos.lecciones.index', $modulo) }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Vista previa -->
        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Vista Previa de la Lección</h6>
                        <small class="text-muted">Ver cómo se verá para los estudiantes</small>
                    </div>
                    <a href="{{ route('lecciones.show', $leccion) }}"
                       class="btn btn-primary"
                       target="_blank">
                        <i class="bi bi-eye"></i> Ver Vista Previa
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
