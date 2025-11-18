<?php

namespace App\Helpers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionHelper
{
    /**
     * Obtener todos los permisos agrupados por categoría
     */
    public static function getPermissionsGrouped()
    {
        $permissions = Permission::all();
        
        $grouped = [
            'Cursos' => $permissions->filter(fn($p) => strpos($p->name, 'cursos') !== false),
            'Blogs' => $permissions->filter(fn($p) => strpos($p->name, 'blogs') !== false),
            'Usuarios' => $permissions->filter(fn($p) => strpos($p->name, 'users') !== false),
            'Sistema' => $permissions->filter(fn($p) => strpos($p->name, 'manage') !== false),
        ];
        
        return array_filter($grouped, fn($group) => count($group) > 0);
    }

    /**
     * Obtener descripción amigable de un permiso
     */
    public static function getPermissionLabel($permissionName)
    {
        $labels = [
            'view_cursos' => 'Ver Cursos',
            'create_cursos' => 'Crear Cursos',
            'edit_cursos' => 'Editar Cursos',
            'delete_cursos' => 'Eliminar Cursos',
            'view_blogs' => 'Ver Blogs',
            'create_blogs' => 'Crear Blogs',
            'edit_blogs' => 'Editar Blogs',
            'delete_blogs' => 'Eliminar Blogs',
            'view_users' => 'Ver Usuarios',
            'create_users' => 'Crear Usuarios',
            'edit_users' => 'Editar Usuarios',
            'delete_users' => 'Eliminar Usuarios',
            'manage_roles' => 'Gestionar Roles',
            'manage_permissions' => 'Gestionar Permisos',
        ];
        
        return $labels[$permissionName] ?? $permissionName;
    }

    /**
     * Obtener descripción amigable de un rol
     */
    public static function getRoleDescription($roleName)
    {
        $descriptions = [
            'Administrador' => 'Control total del sistema',
            'Editor' => 'Puede gestionar cursos, blogs y ver usuarios',
            'Usuario' => 'Acceso de lectura solamente',
        ];
        
        return $descriptions[$roleName] ?? '';
    }

    /**
     * Contar usuarios por rol
     */
    public static function getUserCountByRole($roleId)
    {
        return Role::find($roleId)?->users()->count() ?? 0;
    }

    /**
     * Obtener permisos de un rol en formato legible
     */
    public static function getRolePermissionsLabeled($role)
    {
        return $role->permissions->map(fn($p) => self::getPermissionLabel($p->name));
    }
}
