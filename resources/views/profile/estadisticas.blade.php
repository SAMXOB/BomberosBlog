@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Mis Estadísticas</h2>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-book-fill text-primary" style="font-size: 3rem;"></i>
                    <h3 class="mt-3">{{ $stats['cursosInscritos'] }}</h3>
                    <p class="text-muted">Cursos Inscritos</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                    <h3 class="mt-3">{{ $stats['cursosCompletados'] }}</h3>
                    <p class="text-muted">Cursos Completados</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-play-circle-fill text-info" style="font-size: 3rem;"></i>
                    <h3 class="mt-3">{{ $stats['cursosActivos'] }}</h3>
                    <p class="text-muted">Cursos Activos</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-journal-text text-warning" style="font-size: 3rem;"></i>
                    <h3 class="mt-3">{{ $stats['cursosCreados'] }}</h3>
                    <p class="text-muted">Cursos Creados</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Progreso Promedio</h5>
                    <div class="progress mx-auto" style="height: 30px; max-width: 500px;">
                        <div class="progress-bar bg-success"
                             role="progressbar"
                             style="width: {{ $stats['progresoPromedio'] }}%"
                             aria-valuenow="{{ $stats['progresoPromedio'] }}"
                             aria-valuemin="0"
                             aria-valuemax="100">
                            {{ number_format($stats['progresoPromedio'], 1) }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('profile.show') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver al perfil
        </a>
        <a href="{{ route('cursos.mis-cursos') }}" class="btn btn-primary">
            <i class="bi bi-book"></i> Ver mis cursos
        </a>
    </div>
</div>
@endsection
