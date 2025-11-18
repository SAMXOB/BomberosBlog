@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Editar Rol</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rols.index') }}">Roles</a></li>
                <li class="breadcrumb-item active">Nuevo Rol</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
            {{-- Roles --}}
            <div class="card shadow mt-3">
                <div class="card-body">
                    <h3 class="card-title">Editar rol</h3>
                    <form action="{{ route('rols.update', $rol->id)}}" method="POST" id="frmCreate">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="rol_id" value="{{ $rol->id }}">
                        <input type="hidden" name="permissions" id="permissions">

                        <div class="col-md-12">
                            <div class="form-floating">
                                <input type="text" name="name"
                                placeholder="Nombre..."
                                class="form-control"
                                value="{{ $rol->name }}">
                                <label for="">Nuevo nombre de rol</label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            {{-- Permisos --}}
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h3 class="car-tittle">Nuevos permisos</h3>

                    <div class="row">
                        @foreach($modules as $key => $module)

                            <div class="col-md-3 mt-3">

                                <h5>{{ $key }}</h5>

                                @foreach($module as $item)
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input permission" data-permission-id="{{ $item->id }}" id="permission_{{ $item->id }}">

                                        <label for="permission_{{ $item->id }}" class="form-check-label">{{ $item->description }}</label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button type="button" class="btn btn-primary" id="btnSave">Guardar</button>
                <a href="{{ route('rols.index') }}" class="btn btn-secondary">Volver</a>
            </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnSave = document.getElementById('btnSave');
            const form = document.getElementById('frmCreate');

            btnSave.addEventListener('click', function () {
                const permissions = document.querySelectorAll('.permission:checked');

                const permissionIds = Array.from(permissions).map(p => p.dataset.permissionId);

                document.getElementById('permissions').value = JSON.stringify(permissionIds);

                console.log('Permissions:', permissionIds);

                form.submit(); // <== Ahora enviamos el form con datos bien cargados
            });
        });
    </script>
@endsection