@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('cursos.disponibles') }}">Cursos</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cursos.show', $curso) }}">{{ Str::limit($curso->titulo, 30) }}</a></li>
                    <li class="breadcrumb-item active">{{ $leccion->titulo }}</li>
                </ol>
            </nav>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card shadow-lg">
                <!-- Header de la lección -->
                <div class="card-header bg-dark text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $leccion->titulo }}</h3>
                            <small>{{ $modulo->titulo }} - {{ $curso->titulo }}</small>
                        </div>
                        <div>
                            @if($completada)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle-fill"></i> Completada
                                </span>
                            @endif
                            @if($leccion->tipo === 'video')
                                <span class="badge bg-primary">Video</span>
                            @elseif($leccion->tipo === 'quiz')
                                <span class="badge bg-warning">Quiz</span>
                            @elseif($leccion->tipo === 'archivo')
                                <span class="badge bg-info">Archivo</span>
                            @else
                                <span class="badge bg-secondary">Lectura</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contenido de la lección -->
                <div class="card-body">
                    <!-- Video si es tipo video -->
                    @if($leccion->tipo === 'video' && $leccion->url_recurso)
                        <div class="mb-4">
                            <div class="ratio ratio-16x9">
                                @if(str_contains($leccion->url_recurso, 'youtube.com') || str_contains($leccion->url_recurso, 'youtu.be'))
                                    @php
                                        $videoId = null;
                                        if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $leccion->url_recurso, $matches)) {
                                            $videoId = $matches[1];
                                        } elseif (preg_match('/youtu\.be\/([^?]+)/', $leccion->url_recurso, $matches)) {
                                            $videoId = $matches[1];
                                        }
                                    @endphp
                                    @if($videoId)
                                        <iframe src="https://www.youtube.com/embed/{{ $videoId }}"
                                                allowfullscreen></iframe>
                                    @endif
                                @else
                                    <video controls class="w-100">
                                        <source src="{{ $leccion->url_recurso }}" type="video/mp4">
                                        Tu navegador no soporta videos.
                                    </video>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Archivo descargable -->
                    @if($leccion->tipo === 'archivo' && $leccion->url_recurso)
                        <div class="alert alert-info">
                            <i class="bi bi-download"></i>
                            <a href="{{ $leccion->url_recurso }}" target="_blank" class="alert-link">
                                Descargar archivo adjunto
                            </a>
                        </div>
                    @endif

                    <!-- Contenido de texto -->
                    <div class="leccion-contenido">
                        {!! nl2br(e($leccion->contenido)) !!}
                    </div>

                    <hr>

                    <!-- Duración -->
                    @if($leccion->duracion_minutos > 0)
                        <p class="text-muted">
                            <i class="bi bi-clock"></i> Duración estimada: {{ $leccion->duracion_minutos }} minutos
                        </p>
                    @endif

                    <!-- Botón de completar -->
                    @auth
                        @if(auth()->user()->estaInscritoEn($curso->id))
                            <div class="d-grid gap-2 mt-4">
                                @if(!$completada)
                                    <button type="button"
                                            class="btn btn-success btn-lg"
                                            onclick="completarLeccion({{ $leccion->id }})">
                                        <i class="bi bi-check-circle"></i> Marcar como completada
                                    </button>
                                @else
                                    <button type="button"
                                            class="btn btn-outline-secondary"
                                            onclick="descompletarLeccion({{ $leccion->id }})">
                                        <i class="bi bi-x-circle"></i> Desmarcar completada
                                    </button>
                                @endif
                            </div>
                        @endif
                    @endauth
                </div>

                <!-- Navegación entre lecciones -->
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between">
                        @php
                            $lecciones = $modulo->lecciones;
                            $currentIndex = $lecciones->search(fn($l) => $l->id === $leccion->id);
                            $anterior = $currentIndex > 0 ? $lecciones[$currentIndex - 1] : null;
                            $siguiente = $currentIndex < $lecciones->count() - 1 ? $lecciones[$currentIndex + 1] : null;
                        @endphp

                        @if($anterior)
                            <a href="{{ route('lecciones.show', $anterior) }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left"></i> Lección anterior
                            </a>
                        @else
                            <div></div>
                        @endif

                        <a href="{{ route('cursos.show', $curso) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list"></i> Ver contenido del curso
                        </a>

                        @if($siguiente)
                            <a href="{{ route('lecciones.show', $siguiente) }}" class="btn btn-primary">
                                Siguiente lección <i class="bi bi-arrow-right"></i>
                            </a>
                        @else
                            <div></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function completarLeccion(leccionId) {
    fetch(`/lecciones/${leccionId}/completar`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function descompletarLeccion(leccionId) {
    fetch(`/lecciones/${leccionId}/descompletar`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endsection
