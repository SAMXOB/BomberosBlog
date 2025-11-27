@extends('admin.layout')

@section('title', 'Lecciones - ' . $modulo->titulo)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>📖 Lecciones del Módulo</h2>
        <p class="text-muted mb-0">
            <strong>Módulo:</strong> {{ $modulo->titulo }} |
            <strong>Curso:</strong> {{ $modulo->curso->titulo }}
        </p>
    </div>
    <a href="{{ route('admin.modulos.lecciones.create', $modulo) }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Crear Lección
    </a>
</div>

<div class="mb-3">
    <a href="{{ route('admin.cursos.modulos.index', $modulo->curso) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver a módulos
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($lecciones->isEmpty())
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-book" style="font-size: 4rem; color: #ccc;"></i>
            <h4 class="mt-3">No hay lecciones en este módulo</h4>
            <p class="text-muted">Agrega lecciones para comenzar a enseñar.</p>
            <a href="{{ route('admin.modulos.lecciones.create', $modulo) }}" class="btn btn-primary mt-2">
                <i class="bi bi-plus-circle"></i> Crear Primera Lección
            </a>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Lecciones ({{ $lecciones->count() }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Título</th>
                            <th width="100">Tipo</th>
                            <th width="100">Duración</th>
                            <th width="100">Estado</th>
                            <th width="80">Gratis</th>
                            <th width="200" class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lecciones as $leccion)
                            <tr>
                                <td>{{ $leccion->orden }}</td>
                                <td>
                                    <strong>{{ $leccion->titulo }}</strong>
                                    <br><small class="text-muted">{{ Str::limit($leccion->contenido, 50) }}</small>
                                </td>
                                <td>
                                    @if($leccion->tipo === 'video')
                                        <span class="badge bg-primary">
                                            <i class="bi bi-play-circle"></i> Video
                                        </span>
                                    @elseif($leccion->tipo === 'archivo')
                                        <span class="badge bg-info">
                                            <i class="bi bi-file-earmark"></i> Archivo
                                        </span>
                                    @elseif($leccion->tipo === 'quiz')
                                        <span class="badge bg-warning">
                                            <i class="bi bi-question-circle"></i> Quiz
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-file-text"></i> Texto
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i> {{ $leccion->duracion_minutos }} min
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $leccion->publicado ? 'success' : 'secondary' }}">
                                        {{ $leccion->publicado ? 'Publicado' : 'Borrador' }}
                                    </span>
                                </td>
                                <td>
                                    @if($leccion->es_gratis)
                                        <span class="badge bg-success">
                                            <i class="bi bi-gift"></i> Sí
                                        </span>
                                    @else
                                        <span class="text-muted">No</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('lecciones.show', $leccion) }}"
                                           class="btn btn-outline-info"
                                           target="_blank"
                                           title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.modulos.lecciones.edit', [$modulo, $leccion]) }}"
                                           class="btn btn-outline-warning"
                                           title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.modulos.lecciones.destroy', [$modulo, $leccion]) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('¿Eliminar esta lección?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="row text-muted">
                <div class="col-md-6">
                    <small><strong>Total lecciones:</strong> {{ $lecciones->count() }}</small>
                </div>
                <div class="col-md-6 text-end">
                    <small><strong>Duración total:</strong> {{ $lecciones->sum('duracion_minutos') }} minutos</small>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
