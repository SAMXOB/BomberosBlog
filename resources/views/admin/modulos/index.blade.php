@extends('admin.layout')

@section('title', 'Módulos - ' . $curso->titulo)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>📚 Módulos del Curso</h2>
        <p class="text-muted mb-0">{{ $curso->titulo }}</p>
    </div>
    <a href="{{ route('admin.cursos.modulos.create', $curso) }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Crear Módulo
    </a>
</div>

<div class="mb-3">
    <a href="{{ route('admin.cursos.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver a cursos
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($modulos->isEmpty())
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-folder2-open" style="font-size: 4rem; color: #ccc;"></i>
            <h4 class="mt-3">No hay módulos en este curso</h4>
            <p class="text-muted">Comienza creando el primer módulo para estructurar tu contenido.</p>
            <a href="{{ route('admin.cursos.modulos.create', $curso) }}" class="btn btn-primary mt-2">
                <i class="bi bi-plus-circle"></i> Crear Primer Módulo
            </a>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Módulos ({{ $modulos->count() }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Título</th>
                            <th width="120">Lecciones</th>
                            <th width="120">Duración</th>
                            <th width="100">Estado</th>
                            <th width="200" class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="modulos-sortable">
                        @foreach($modulos as $modulo)
                            <tr data-id="{{ $modulo->id }}">
                                <td>
                                    <i class="bi bi-grip-vertical text-muted" style="cursor: move;"></i>
                                    {{ $modulo->orden }}
                                </td>
                                <td>
                                    <strong>{{ $modulo->titulo }}</strong>
                                    @if($modulo->descripcion)
                                        <br><small class="text-muted">{{ Str::limit($modulo->descripcion, 60) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $modulo->lecciones_count }} lecciones
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i> {{ $modulo->duracionTotal() }} min
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $modulo->publicado ? 'success' : 'secondary' }}">
                                        {{ $modulo->publicado ? 'Publicado' : 'Borrador' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.modulos.lecciones.index', $modulo) }}"
                                           class="btn btn-outline-primary"
                                           title="Ver lecciones">
                                            <i class="bi bi-list-ul"></i> Lecciones
                                        </a>
                                        <a href="{{ route('admin.cursos.modulos.edit', [$curso, $modulo]) }}"
                                           class="btn btn-outline-warning"
                                           title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.cursos.modulos.destroy', [$curso, $modulo]) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('¿Eliminar este módulo y todas sus lecciones?')">
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
    </div>

    <!-- Información sobre reordenamiento -->
    <div class="alert alert-info mt-3">
        <i class="bi bi-info-circle"></i>
        <strong>Tip:</strong> Arrastra los módulos para cambiar su orden. Los cambios se guardan automáticamente.
    </div>
@endif

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tbody = document.getElementById('modulos-sortable');
    if (tbody) {
        new Sortable(tbody, {
            handle: '.bi-grip-vertical',
            animation: 150,
            onEnd: function(evt) {
                const modulos = [];
                document.querySelectorAll('#modulos-sortable tr').forEach((row, index) => {
                    modulos.push({
                        id: row.dataset.id,
                        orden: index + 1
                    });
                });

                fetch('{{ route("admin.cursos.modulos.reorder", $curso) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ modulos: modulos })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Actualizar números de orden en la UI
                        document.querySelectorAll('#modulos-sortable tr').forEach((row, index) => {
                            row.querySelector('td:first-child').innerHTML =
                                `<i class="bi bi-grip-vertical text-muted" style="cursor: move;"></i> ${index + 1}`;
                        });
                    }
                });
            }
        });
    }
});
</script>
@endsection
