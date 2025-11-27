@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="mb-4">Cursos Disponibles</h1>

            <!-- Filtros y búsqueda -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('cursos.disponibles') }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Buscar</label>
                                <input type="text" name="search" class="form-control"
                                       placeholder="Buscar por título o descripción..."
                                       value="{{ $search }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Categoría</label>
                                <select name="categoria" class="form-select">
                                    <option value="">Todas las categorías</option>
                                    @foreach($categorias as $cat)
                                        <option value="{{ $cat }}" {{ $categoria == $cat ? 'selected' : '' }}>
                                            {{ ucfirst($cat) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search"></i> Filtrar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning">{{ session('warning') }}</div>
            @endif

            <!-- Grid de cursos -->
            <div class="row">
                @forelse($cursos as $curso)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">{{ $curso->titulo }}</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">{{ Str::limit($curso->descripcion, 100) }}</p>
                                <p class="text-muted">
                                    <small>
                                        <strong>Categoría:</strong> {{ ucfirst($curso->categoria) }}<br>
                                        <strong>Instructor:</strong> {{ $curso->user->name }}<br>
                                        <strong>Inscritos:</strong> {{ $curso->inscritos->count() }} estudiantes
                                    </small>
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('cursos.show', $curso) }}" class="btn btn-sm btn-outline-primary">
                                        Ver detalles
                                    </a>
                                    @auth
                                        @if(auth()->user()->estaInscritoEn($curso->id))
                                            <span class="badge bg-success">Ya inscrito</span>
                                        @else
                                            <form action="{{ route('cursos.inscribir', $curso) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    Inscribirme
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-sm btn-secondary">
                                            Inicia sesión
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            No se encontraron cursos disponibles.
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center">
                {{ $cursos->appends(['search' => $search, 'categoria' => $categoria])->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
