<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SetupAdmin extends Command
{
    protected $signature = 'setup:admin';
    protected $description = 'Asignar rol de Administrador al primer usuario';

    public function handle()
    {
        $this->info('🚀 Configurando Administrador...\n');

        $user = User::first();

        if (!$user) {
            $this->error('❌ No hay usuarios en la base de datos.');
            $this->info('Crea un usuario primero con: php artisan tinker');
            return 1;
        }

        $this->info("✅ Usuario encontrado: {$user->name} ({$user->email})");

        $user->syncRoles(['Administrador']);

        $this->info("✅ Rol Administrador asignado");
        $this->info("\n═══════════════════════════════════════════════════");
        $this->info("✨ ¡Configuración completada!");
        $this->info("═══════════════════════════════════════════════════\n");

        $this->info("📍 Accede a: http://127.0.0.1:8000/admin");
        $this->info("👤 Email: {$user->email}");
        $this->info("🔐 Necesitarás tu contraseña\n");

        return 0;
    }
}
