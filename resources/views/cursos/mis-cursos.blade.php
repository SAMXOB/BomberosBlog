@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Mis Cursos</h1>
                <a href="{{ route('cursos.disponibles') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Explorar más cursos
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="row">
                @forelse($cursosInscritos as $curso)
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-info text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">{{ $curso->titulo }}</h5>
                                    <span class="badge bg-light text-dark">
                                        {{ ucfirst($curso->pivot->estado) }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="card-text">{{ Str::limit($curso->descripcion, 150) }}</p>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <strong>Progreso: {{ $curso->pivot->progreso }}%</strong>
                                    </label>
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar bg-success"
                                             role="progressbar"
                                             style="width: {{ $curso->pivot->progreso }}%"
                                             aria-valuenow="{{ $curso->pivot->progreso }}"
                                             aria-valuemin="0"
                                             aria-valuemax="100">
                                            {{ $curso->pivot->progreso }}%
                                        </div>
                                    </div>
                                </div>

                                <p class="text-muted mb-2">
                                    <small>
                                        <strong>Inscrito:</strong> {{ $curso->pivot->inscrito_at->format('d/m/Y') }}<br>
                                        <strong>Instructor:</strong> {{ $curso->user->name }}<br>
                                        @if($curso->pivot->completado_at)
                                            <strong>Completado:</strong> {{ $curso->pivot->completado_at->format('d/m/Y') }}
                                        @endif
                                    </small>
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('cursos.show', $curso) }}" class="btn btn-sm btn-outline-primary">
                                        Ver curso
                                    </a>
                                    <form action="{{ route('cursos.desinscribir', $curso) }}" method="POST"
                                          onsubmit="return confirm('¿Estás seguro de desinscribirte?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Desinscribirse
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h4>No estás inscrito en ningún curso</h4>
                            <p>Explora nuestro catálogo y comienza a aprender hoy mismo.</p>
                            <a href="{{ route('cursos.disponibles') }}" class="btn btn-primary">
                                Ver cursos disponibles
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Paginación -->
            @if($cursosInscritos->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $cursosInscritos->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
