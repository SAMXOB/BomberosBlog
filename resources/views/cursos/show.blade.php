@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('cursos.disponibles') }}">Cursos</a></li>
                    <li class="breadcrumb-item active">{{ $curso->titulo }}</li>
                </ol>
            </nav>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">{{ $curso->titulo }}</h2>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <span class="badge bg-{{ $curso->estado === 'activo' ? 'success' : 'secondary' }} me-2">
                            {{ ucfirst($curso->estado) }}
                        </span>
                        <span class="badge bg-info text-dark">{{ ucfirst($curso->categoria) }}</span>
                    </div>

                    <h5>Descripción</h5>
                    <p class="lead">{{ $curso->descripcion }}</p>

                    <hr>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Instructor:</strong> {{ $curso->user->name }}</p>
                            <p><strong>Inscritos:</strong> {{ $totalInscritos }} estudiantes</p>
                            @if($totalLecciones > 0)
                                <p><strong>Lecciones:</strong> {{ $totalLecciones }}</p>
                                <p><strong>Duración:</strong> {{ $duracionTotal }} minutos</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p><strong>Creado:</strong> {{ $curso->created_at->format('d/m/Y') }}</p>
                            <p><strong>Última actualización:</strong> {{ $curso->updated_at->format('d/m/Y') }}</p>
                            @if($estaInscrito && $progreso > 0)
                                <p><strong>Tu progreso:</strong> {{ $progreso }}%</p>
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progreso }}%"></div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Contenido del curso: Módulos y Lecciones -->
                    @if($curso->modulosPublicados->count() > 0)
                        <hr>
                        <h4 class="mb-3">📚 Contenido del curso</h4>
                        <div class="accordion" id="modulosAccordion">
                            @foreach($curso->modulosPublicados as $index => $modulo)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $modulo->id }}">
                                        <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapse{{ $modulo->id }}">
                                            <strong>{{ $modulo->titulo }}</strong>
                                            <span class="badge bg-secondary ms-2">{{ $modulo->lecciones->count() }} lecciones</span>
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $modulo->id }}"
                                         class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                         data-bs-parent="#modulosAccordion">
                                        <div class="accordion-body">
                                            @if($modulo->descripcion)
                                                <p class="text-muted">{{ $modulo->descripcion }}</p>
                                            @endif

                                            <ul class="list-group">
                                                @foreach($modulo->leccionesPublicadas as $leccion)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div>
                                                            @if($estaInscrito)
                                                                @if(auth()->user()->completoLeccion($leccion->id))
                                                                    <i class="bi bi-check-circle-fill text-success"></i>
                                                                @else
                                                                    <i class="bi bi-circle text-muted"></i>
                                                                @endif
                                                            @endif

                                                            @if($leccion->tipo === 'video')
                                                                <i class="bi bi-play-circle text-primary"></i>
                                                            @elseif($leccion->tipo === 'archivo')
                                                                <i class="bi bi-file-earmark text-info"></i>
                                                            @elseif($leccion->tipo === 'quiz')
                                                                <i class="bi bi-question-circle text-warning"></i>
                                                            @else
                                                                <i class="bi bi-file-text text-secondary"></i>
                                                            @endif

                                                            @if($estaInscrito || $leccion->es_gratis)
                                                                <a href="{{ route('lecciones.show', $leccion) }}">
                                                                    {{ $leccion->titulo }}
                                                                </a>
                                                            @else
                                                                <span class="text-muted">{{ $leccion->titulo }}</span>
                                                                <i class="bi bi-lock-fill text-muted ms-1"></i>
                                                            @endif

                                                            @if($leccion->es_gratis)
                                                                <span class="badge bg-success ms-1">Gratis</span>
                                                            @endif
                                                        </div>

                                                        <span class="text-muted">
                                                            <small>{{ $leccion->duracion_minutos }} min</small>
                                                        </span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <hr>
                    @endif

                    @auth
                        @if($estaInscrito)
                            <div class="alert alert-success">
                                <h5><i class="bi bi-check-circle"></i> Ya estás inscrito en este curso</h5>
                                <p class="mb-0">Continúa tu aprendizaje desde <a href="{{ route('cursos.mis-cursos') }}">Mis Cursos</a></p>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('cursos.mis-cursos') }}" class="btn btn-primary">
                                    Ir a mis cursos
                                </a>
                                <form action="{{ route('cursos.desinscribir', $curso) }}" method="POST"
                                      onsubmit="return confirm('¿Estás seguro de desinscribirte de este curso?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        Desinscribirse
                                    </button>
                                </form>
                            </div>
                        @else
                            @if($curso->estado === 'activo')
                                <form action="{{ route('cursos.inscribir', $curso) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-lg w-100">
                                        <i class="bi bi-check-circle"></i> Inscribirme en este curso
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-warning">
                                    Este curso no está disponible para inscripciones en este momento.
                                </div>
                            @endif
                        @endif
                    @else
                        <div class="alert alert-info">
                            <h5>¿Te interesa este curso?</h5>
                            <p class="mb-0">
                                <a href="{{ route('login') }}" class="btn btn-primary">Inicia sesión</a>
                                para inscribirte y comenzar a aprender.
                            </p>
                        </div>
                    @endauth
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('cursos.disponibles') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver a cursos
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
