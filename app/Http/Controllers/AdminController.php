<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Curso;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    /**
     * Dashboard de administración
     */
    public function dashboard()
    {
        $users = User::count();
        $cursos = Curso::count();
        $roles = Role::count();
        $permissions = Permission::count();

        return view('admin.dashboard', compact('users', 'cursos', 'roles', 'permissions'));
    }

    // ==================== GESTIÓN DE USUARIOS ====================

    /**
     * Listar usuarios
     */
    public function indexUsers()
    {
        $users = User::with('roles')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Formulario para crear usuario
     */
    public function createUser()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Guardar nuevo usuario
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'roles' => 'required|array',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $user->syncRoles($validated['roles']);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado exitosamente');
    }

    /**
     * Formulario para editar usuario
     */
    public function editUser(User $user)
    {
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Actualizar usuario
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'roles' => 'required|array',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($validated['password']) {
            $user->update(['password' => bcrypt($validated['password'])]);
        }

        $user->syncRoles($validated['roles']);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado exitosamente');
    }

    /**
     * Eliminar usuario
     */
    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propio usuario');
        }

        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario eliminado exitosamente');
    }

    // ==================== GESTIÓN DE CURSOS ====================

    /**
     * Listar cursos
     */
    public function indexCursos()
    {
        $cursos = Curso::with('user')->paginate(15);
        return view('admin.cursos.index', compact('cursos'));
    }

    /**
     * Formulario para crear curso
     */
    public function createCurso()
    {
        return view('admin.cursos.create');
    }

    /**
     * Guardar nuevo curso
     */
    public function storeCurso(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'categoria' => 'required|string|max:255',
            'estado' => 'required|in:activo,inactivo,draft',
        ]);

        Curso::create([
            ...$validated,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('admin.cursos.index')
            ->with('success', 'Curso creado exitosamente');
    }

    /**
     * Formulario para editar curso
     */
    public function editCurso(Curso $curso)
    {
        return view('admin.cursos.edit', compact('curso'));
    }

    /**
     * Actualizar curso
     */
    public function updateCurso(Request $request, Curso $curso)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'categoria' => 'required|string|max:255',
            'estado' => 'required|in:activo,inactivo,draft',
        ]);

        $curso->update($validated);

        return redirect()->route('admin.cursos.index')
            ->with('success', 'Curso actualizado exitosamente');
    }

    /**
     * Eliminar curso
     */
    public function destroyCurso(Curso $curso)
    {
        $curso->delete();
        return redirect()->route('admin.cursos.index')
            ->with('success', 'Curso eliminado exitosamente');
    }

    // ==================== GESTIÓN DE ROLES Y PERMISOS ====================

    /**
     * Listar roles
     */
    public function indexRoles()
    {
        $roles = Role::with('permissions')->paginate(15);
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Formulario para crear rol
     */
    public function createRole()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Guardar nuevo rol
     */
    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'required|array',
        ]);

        $role = Role::create(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions']);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rol creado exitosamente');
    }

    /**
     * Formulario para editar rol
     */
    public function editRole(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Actualizar rol
     */
    public function updateRole(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'required|array',
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions']);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rol actualizado exitosamente');
    }

    /**
     * Eliminar rol
     */
    public function destroyRole(Role $role)
    {
        if ($role->users()->count() > 0) {
            return back()->with('error', 'No puedes eliminar un rol que está asignado a usuarios');
        }

        $role->delete();
        return redirect()->route('admin.roles.index')
            ->with('success', 'Rol eliminado exitosamente');
    }
}
