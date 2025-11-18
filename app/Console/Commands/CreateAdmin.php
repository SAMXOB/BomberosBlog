<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    protected $signature = 'create:admin';
    protected $description = 'Crear un usuario administrador';

    public function handle()
    {
        $this->info('🚀 Creando usuario administrador...\n');

        // Verificar si ya existe
        $existing = User::where('email', 'admin@test.com')->first();

        if ($existing) {
            $this->info('✅ Usuario admin@test.com ya existe');
            $existing->syncRoles(['Administrador']);
            $this->info('✅ Rol asignado\n');
            return 0;
        }

        // Crear usuario
        $user = User::create([
            'name' => 'Administrador',
            'email' => 'admin@test.com',
            'password' => bcrypt('admin123'),
        ]);

        $this->info("✅ Usuario creado: {$user->email}");

        // Asignar rol
        $user->assignRole('Administrador');
        $this->info('✅ Rol Administrador asignado');

        $this->info("\n═══════════════════════════════════════════════════");
        $this->info("✨ ¡Usuario creado exitosamente!");
        $this->info("═══════════════════════════════════════════════════\n");

        $this->info("📍 Accede a: http://127.0.0.1:8000/login");
        $this->info("👤 Email: admin@test.com");
        $this->info("🔐 Contraseña: admin123\n");

        return 0;
    }
}
