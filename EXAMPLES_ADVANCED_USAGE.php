<?php

/**
 * EJEMPLOS AVANZADOS DE USO DEL SISTEMA DE ROLES Y PERMISOS
 *
 * Este archivo contiene ejemplos de código que puedes usar
 * en diferentes partes de tu aplicación
 */

// ============================================================
// 1. EN CONTROLADORES
// ============================================================

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    /**
     * Mostrar solo los cursos que el usuario puede ver
     */
    public function index()
    {
        $user = auth()->user();

        // Opción 1: Verificar permiso
        if (!$user->can('view_cursos')) {
            return redirect('/')->with('error', 'No tienes acceso');
        }

        // Opción 2: Usar authorize (lanza 403 automáticamente)
        $this->authorize('view_cursos');

        $cursos = Curso::all();
        return view('cursos.index', compact('cursos'));
    }

    /**
     * Editar solo tu propio curso si eres editor
     */
    public function edit(Curso $curso)
    {
        // Si es editor, solo puede editar sus propios cursos
        if (auth()->user()->hasRole('editor') && $curso->user_id !== auth()->id()) {
            abort(403, 'No puedes editar este curso');
        }

        $this->authorize('edit_cursos');
        return view('cursos.edit', compact('curso'));
    }

    /**
     * Asignar automáticamente el usuario logueado como creador
     */
    public function store(Request $request)
    {
        $this->authorize('create_cursos');

        $curso = Curso::create([
            ...$request->validated(),
            'user_id' => auth()->id(),  // Asignar usuario automáticamente
        ]);

        return redirect()->route('cursos.show', $curso);
    }
}

// ============================================================
// 2. EN VISTAS (BLADE TEMPLATES)
// ============================================================

?>

<!-- Mostrar botones según permisos -->
<div class="actions">
    @can('edit_cursos')
        <a href="{{ route('cursos.edit', $curso) }}" class="btn btn-warning">Editar</a>
    @endcan

    @can('delete_cursos')
        <button class="btn btn-danger" onclick="deleteCurso({{ $curso->id }})">Eliminar</button>
    @endcan

    @cannot('delete_cursos')
        <p class="text-muted">No tienes permiso para eliminar</p>
    @endcannot
</div>

<!-- Mostrar panel admin solo para administradores -->
@role('Administrador')
    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Panel Admin</a>
@endrole

<!-- Verificar múltiples permisos -->
@canany(['edit_cursos', 'delete_cursos'])
    <div class="admin-tools">
        <!-- Herramientas de administración -->
    </div>
@endcanany

<!-- Verificar rol -->
@hasrole('Editor')
    <div class="editor-panel">
        <!-- Panel para editores -->
    </div>
@endhasrole

<?php

// ============================================================
// 3. COMANDOS ÚTILES DE ARTISAN
// ============================================================

/*

# Ver todos los roles
php artisan tinker
>>> Spatie\Permission\Models\Role::all()

# Ver todos los permisos
>>> Spatie\Permission\Models\Permission::all()

# Ver roles y permisos de un usuario
>>> $user = App\Models\User::find(1)
>>> $user->roles
>>> $user->permissions

# Crear un nuevo rol
>>> $role = Spatie\Permission\Models\Role::create(['name' => 'Moderador'])
>>> $role->syncPermissions(['view_cursos', 'edit_cursos', 'view_users'])

# Asignar rol a usuario
>>> $user->assignRole('Moderador')

# Ver si usuario tiene rol
>>> $user->hasRole('Moderador')

# Ver si usuario tiene permiso
>>> $user->hasPermissionTo('edit_cursos')

*/

// ============================================================
// 4. EN CONSULTAS (QUERIES)
// ============================================================

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Curso;

class QueryExamples
{
    /**
     * Obtener todos los cursos creados por editores
     */
    public function cursosPorEditores()
    {
        return Curso::whereHas('user', function($query) {
            $query->whereHas('roles', function($q) {
                $q->where('name', 'Editor');
            });
        })->get();
    }

    /**
     * Obtener usuarios sin rol asignado
     */
    public function usuariosSinRol()
    {
        return User::whereDoesntHave('roles')->get();
    }

    /**
     * Obtener usuarios que tienen permiso específico
     */
    public function usuariosConPermiso($permissionName)
    {
        return User::whereHas('roles', function($query) use ($permissionName) {
            $query->whereHas('permissions', function($q) use ($permissionName) {
                $q->where('name', $permissionName);
            });
        })->get();
    }

    /**
     * Obtener todos los administradores
     */
    public function obtenerAdmins()
    {
        return User::role('Administrador')->get();
    }

    /**
     * Contar cursos por usuario
     */
    public function cursosPorUsuario($userId)
    {
        return Curso::where('user_id', $userId)->count();
    }
}

// ============================================================
// 5. MIDDLEWARE PERSONALIZADO
// ============================================================

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckCursoOwner
{
    /**
     * Verificar que el usuario es dueño del curso
     */
    public function handle(Request $request, Closure $next)
    {
        $curso = $request->route('curso');

        if ($curso->user_id !== auth()->id() && !auth()->user()->hasRole('Administrador')) {
            abort(403, 'No tienes permiso para acceder a este curso');
        }

        return $next($request);
    }
}

// Usar en rutas:
// Route::put('/cursos/{curso}', 'CursoController@update')
//     ->middleware(['auth', 'permission:edit_cursos', 'check.curso.owner']);

// ============================================================
// 6. EVENTOS Y LISTENERS
// ============================================================

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class UserRoleAssigned
{
    use Dispatchable;

    public function __construct(public $user, public $role) {}
}

// Listener
namespace App\Listeners;

use App\Events\UserRoleAssigned;

class SendRoleAssignedNotification
{
    public function handle(UserRoleAssigned $event)
    {
        // Enviar email notificando del nuevo rol
        // $event->user->notify(new RoleAssignedNotification($event->role));
    }
}

// ============================================================
// 7. SEEDER PERSONALIZADO ADICIONAL
// ============================================================

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class AdditionalRolesSeeder extends Seeder
{
    public function run()
    {
        // Crear rol Moderador
        $moderadorRole = Role::firstOrCreate(['name' => 'Moderador']);
        $moderadorRole->syncPermissions([
            'view_cursos', 'view_blogs', 'view_users',
            'edit_cursos', 'edit_blogs', // Puede editar pero no eliminar
        ]);

        // Crear rol Colaborador
        $colaboradorRole = Role::firstOrCreate(['name' => 'Colaborador']);
        $colaboradorRole->syncPermissions([
            'create_blogs', 'view_blogs', 'edit_blogs', // Solo sus propios blogs
        ]);
    }
}

// ============================================================
// 8. VALIDACIÓN PERSONALIZADA
// ============================================================

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        // Solo administradores pueden crear usuarios
        return auth()->user()->hasRole('Administrador');
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ];
    }

    public function messages()
    {
        return [
            'roles.required' => 'Debe asignar al menos un rol',
        ];
    }
}

// ============================================================
// 9. SCOPES ÚTILES
// ============================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Curso extends Model
{
    /**
     * Scope para obtener solo cursos activos
     */
    public function scopeActivos(Builder $query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope para obtener cursos del usuario actual
     */
    public function scopeMios(Builder $query)
    {
        return $query->where('user_id', auth()->id());
    }

    /**
     * Scope para obtener cursos que puedo editar
     */
    public function scopeQueEdito(Builder $query)
    {
        if (auth()->user()->hasRole('Administrador')) {
            return $query; // Admin ve todo
        }

        return $query->where('user_id', auth()->id());
    }
}

// Uso:
// $misCursos = Curso::mios()->activos()->get();
// $cursosQueEdito = Curso::queEdito()->get();

?>

<!-- ============================================================
10. PRUEBAS (TESTS)
============================================================ -->

<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    /**
     * Verificar que usuario sin permisos no puede crear cursos
     */
    public function test_usuario_sin_permisos_no_puede_crear_cursos()
    {
        $user = User::factory()->create();
        $user->assignRole('Usuario');

        $this->actingAs($user)
            ->get('/admin/cursos/crear')
            ->assertStatus(403);
    }

    /**
     * Verificar que editor puede crear cursos
     */
    public function test_editor_puede_crear_cursos()
    {
        $user = User::factory()->create();
        $user->assignRole('Editor');

        $this->actingAs($user)
            ->get('/admin/cursos/crear')
            ->assertStatus(200);
    }

    /**
     * Verificar que admin puede hacer todo
     */
    public function test_admin_tiene_todos_permisos()
    {
        $user = User::factory()->create();
        $user->assignRole('Administrador');

        $this->assertTrue($user->can('create_cursos'));
        $this->assertTrue($user->can('delete_users'));
        $this->assertTrue($user->can('manage_roles'));
    }
}

?>
