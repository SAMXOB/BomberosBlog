<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel de Administración')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        .sidebar h2 {
            margin-bottom: 30px;
            font-size: 20px;
        }
        .sidebar nav ul {
            list-style: none;
        }
        .sidebar nav ul li {
            margin-bottom: 15px;
        }
        .sidebar nav ul li a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            display: block;
            transition: all 0.3s ease;
        }
        .sidebar nav ul li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        .main-content {
            margin-left: 250px;
            flex: 1;
            padding: 20px;
        }
        .header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header h1 {
            font-size: 28px;
            color: #333;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .user-info a {
            color: #667eea;
            text-decoration: none;
            padding: 8px 15px;
            border: 1px solid #667eea;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .user-info a:hover {
            background-color: #667eea;
            color: white;
        }
        .content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .alert {
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            border-left: 4px solid;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #28a745;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        .btn-primary {
            background-color: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background-color: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table thead {
            background-color: #f8f9fa;
        }
        table th {
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
        }
        table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }
        table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            font-size: 14px;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-primary {
            background-color: #e7f3ff;
            color: #0066cc;
        }
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .actions a, .actions button {
            padding: 6px 12px;
            font-size: 12px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
        }
        .actions a.edit {
            background-color: #ffc107;
            color: #333;
        }
        .actions button.delete {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>Bomberos Blog</h2>
            <nav>
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}">📊 Dashboard</a></li>
                    @can('view_users')
                    <li><a href="{{ route('admin.users.index') }}">👥 Usuarios</a></li>
                    @endcan
                    @can('view_cursos')
                    <li><a href="{{ route('admin.cursos.index') }}">📚 Cursos</a></li>
                    @endcan
                    @can('manage_roles')
                    <li><a href="{{ route('admin.roles.index') }}">🔐 Roles y Permisos</a></li>
                    @endcan
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header class="header">
                <h1>@yield('title')</h1>
                <div class="user-info">
                    <span>{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-secondary" style="padding: 8px 15px; border: none; cursor: pointer;">Salir</button>
                    </form>
                </div>
            </header>

            <!-- Alerts -->
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    {{ $message }}
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="alert alert-error">
                    {{ $message }}
                </div>
            @endif

            <!-- Content -->
            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
