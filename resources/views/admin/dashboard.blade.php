@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<!-- Estadísticas principales -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom: 10px;">👥 Total Usuarios</h3>
        <p style="font-size: 32px; font-weight: bold;">{{ $stats['totalUsers'] }}</p>
    </div>

    <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom: 10px;">📚 Total Cursos</h3>
        <p style="font-size: 32px; font-weight: bold;">{{ $stats['totalCursos'] }}</p>
        <small>{{ $stats['cursosActivos'] }} activos • {{ $stats['cursosDraft'] }} borradores</small>
    </div>

    <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom: 10px;">✅ Inscripciones</h3>
        <p style="font-size: 32px; font-weight: bold;">{{ $stats['inscripcionesActivas'] }}</p>
        <small>{{ $stats['cursosCompletados'] }} completadas</small>
    </div>

    <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom: 10px;">🔐 Roles/Permisos</h3>
        <p style="font-size: 32px; font-weight: bold;">{{ $stats['totalRoles'] }} / {{ $stats['totalPermissions'] }}</p>
    </div>
</div>

<div class="row mb-4">
    <!-- Cursos más populares -->
    <div class="col-md-6 mb-4">
        <div style="background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h4 style="margin-bottom: 20px;">🔥 Cursos Más Populares</h4>
            @if($cursosPopulares->isEmpty())
                <p class="text-muted">No hay cursos con inscripciones aún</p>
            @else
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Curso</th>
                            <th>Categoría</th>
                            <th>Inscritos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cursosPopulares as $curso)
                            <tr>
                                <td><strong>{{ Str::limit($curso->titulo, 40) }}</strong></td>
                                <td><span class="badge bg-info">{{ ucfirst($curso->categoria) }}</span></td>
                                <td><span class="badge bg-success">{{ $curso->inscritos_count }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Usuarios más activos -->
    <div class="col-md-6 mb-4">
        <div style="background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h4 style="margin-bottom: 20px;">⭐ Usuarios Más Activos</h4>
            @if($usuariosActivos->isEmpty())
                <p class="text-muted">No hay usuarios con inscripciones aún</p>
            @else
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Cursos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuariosActivos as $usuario)
                            <tr>
                                <td><strong>{{ $usuario->name }}</strong></td>
                                <td>{{ Str::limit($usuario->email, 25) }}</td>
                                <td><span class="badge bg-primary">{{ $usuario->cursos_inscritos_count }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

<div class="row mb-4">
    <!-- Cursos recientes -->
    <div class="col-md-6 mb-4">
        <div style="background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h4 style="margin-bottom: 20px;">📝 Cursos Recientes</h4>
            @if($cursosRecientes->isEmpty())
                <p class="text-muted">No hay cursos creados</p>
            @else
                <ul class="list-group">
                    @foreach($cursosRecientes as $curso)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ Str::limit($curso->titulo, 35) }}</strong><br>
                                <small class="text-muted">por {{ $curso->user->name }}</small>
                            </div>
                            <span class="badge bg-{{ $curso->estado === 'activo' ? 'success' : 'secondary' }}">
                                {{ ucfirst($curso->estado) }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Inscripciones recientes -->
    <div class="col-md-6 mb-4">
        <div style="background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h4 style="margin-bottom: 20px;">🆕 Inscripciones Recientes</h4>
            @if($inscripcionesRecientes->isEmpty())
                <p class="text-muted">No hay inscripciones aún</p>
            @else
                <ul class="list-group">
                    @foreach($inscripcionesRecientes->take(5) as $inscripcion)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>{{ $inscripcion->user_name }}</strong><br>
                                    <small class="text-muted">{{ Str::limit($inscripcion->titulo, 30) }}</small>
                                </div>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($inscripcion->inscrito_at)->diffForHumans() }}
                                </small>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>

<div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px;">
    <h2 style="margin-bottom: 20px;">👋 Bienvenido al Panel de Administración</h2>
    <p>Este es tu panel de control para gestionar:</p>
    <ul style="margin-top: 10px; margin-left: 20px;">
        <li>👥 Usuarios y sus roles</li>
        <li>📚 Cursos y contenido educativo</li>
        <li>🔐 Roles y permisos del sistema</li>
        <li>📊 Estadísticas y actividad de la plataforma</li>
    </ul>
</div>
@endsection

