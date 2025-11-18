<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar caché de permisos
        app()['cache']->forget('spatie.permission.cache');

        // Crear permisos para Cursos
        Permission::firstOrCreate(['name' => 'view_cursos']);
        Permission::firstOrCreate(['name' => 'create_cursos']);
        Permission::firstOrCreate(['name' => 'edit_cursos']);
        Permission::firstOrCreate(['name' => 'delete_cursos']);

        // Crear permisos para Blogs/Artículos
        Permission::firstOrCreate(['name' => 'view_blogs']);
        Permission::firstOrCreate(['name' => 'create_blogs']);
        Permission::firstOrCreate(['name' => 'edit_blogs']);
        Permission::firstOrCreate(['name' => 'delete_blogs']);

        // Crear permisos para Usuarios
        Permission::firstOrCreate(['name' => 'view_users']);
        Permission::firstOrCreate(['name' => 'create_users']);
        Permission::firstOrCreate(['name' => 'edit_users']);
        Permission::firstOrCreate(['name' => 'delete_users']);

        // Crear permisos para Roles y Permisos
        Permission::firstOrCreate(['name' => 'manage_roles']);
        Permission::firstOrCreate(['name' => 'manage_permissions']);

        // Crear Roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $editorRole = Role::firstOrCreate(['name' => 'Editor']);
        $usuarioRole = Role::firstOrCreate(['name' => 'Usuario']);

        // Asignar todos los permisos al Administrador
        $adminRole->syncPermissions(Permission::all());

        // Asignar permisos al Editor
        $editorRole->syncPermissions([
            'view_cursos', 'create_cursos', 'edit_cursos',
            'view_blogs', 'create_blogs', 'edit_blogs',
            'view_users',
        ]);

        // Asignar permisos al Usuario
        $usuarioRole->syncPermissions([
            'view_cursos',
            'view_blogs',
        ]);
    }
}
