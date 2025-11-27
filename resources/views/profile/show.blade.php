@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    </div>
                    <h5 class="card-title">{{ $user->name }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>

                    @if($user->roles->isNotEmpty())
                        <span class="badge bg-primary">{{ $user->roles->first()->name }}</span>
                    @endif
                </div>
            </div>

            <div class="list-group">
                <a href="{{ route('profile.show') }}" class="list-group-item list-group-item-action active">
                    <i class="bi bi-person"></i> Mi Perfil
                </a>
                <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-pencil"></i> Editar Perfil
                </a>
                <a href="{{ route('cursos.mis-cursos') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-book"></i> Mis Cursos
                </a>
                <a href="{{ route('profile.estadisticas') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-graph-up"></i> Estadísticas
                </a>
            </div>
        </div>

        <div class="col-md-9">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Información Personal</h4>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200">Nombre:</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Rol:</th>
                            <td>
                                @if($user->roles->isNotEmpty())
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-primary">{{ $role->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">Sin rol asignado</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Miembro desde:</th>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-book"></i> Cursos Inscritos
                            </h5>
                            <h2>{{ $user->cursosInscritos->count() }}</h2>
                            <a href="{{ route('cursos.mis-cursos') }}" class="btn btn-light btn-sm">Ver cursos</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-check-circle"></i> Cursos Completados
                            </h5>
                            <h2>{{ $user->cursosInscritos->where('pivot.estado', 'completado')->count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            @if($user->cursosCreados->isNotEmpty())
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Cursos que he creado</h4>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @foreach($user->cursosCreados->take(5) as $curso)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">{{ $curso->titulo }}</h6>
                                            <small class="text-muted">{{ ucfirst($curso->categoria) }}</small>
                                        </div>
                                        <span class="badge bg-{{ $curso->estado === 'activo' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($curso->estado) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
