@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom: 10px;">Total Usuarios</h3>
        <p style="font-size: 32px; font-weight: bold;">{{ $users }}</p>
    </div>

    <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom: 10px;">Total Cursos</h3>
        <p style="font-size: 32px; font-weight: bold;">{{ $cursos }}</p>
    </div>

    <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom: 10px;">Total Roles</h3>
        <p style="font-size: 32px; font-weight: bold;">{{ $roles }}</p>
    </div>

    <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom: 10px;">Total Permisos</h3>
        <p style="font-size: 32px; font-weight: bold;">{{ $permissions }}</p>
    </div>
</div>

<div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px;">
    <h2 style="margin-bottom: 20px;">Bienvenido al Panel de Administración</h2>
    <p>Este es tu panel de control para gestionar:</p>
    <ul style="margin-top: 10px; margin-left: 20px;">
        <li>👥 Usuarios y sus roles</li>
        <li>📚 Cursos y contenido</li>
        <li>🔐 Roles y permisos del sistema</li>
    </ul>
</div>
@endsection
