<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateTenantUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:create-users {tenant}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear usuarios únicos para un tenant específico';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenant = $this->argument('tenant');
        $connectionName = "tenant_{$tenant}";
        
        $this->info("Creando usuarios para tenant: {$tenant}");
        
        // Limpiar usuarios existentes
        DB::connection($connectionName)->table('usuarios')->truncate();
        
        // Crear usuarios únicos por tenant
        $usuarios = $this->getUsersForTenant($tenant);
        
        foreach ($usuarios as $usuario) {
            DB::connection($connectionName)->table('usuarios')->insert([
                'nombre' => $usuario['nombre'],
                'email' => $usuario['email'],
                'password' => Hash::make('password123'),
                'rol' => $usuario['rol'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $this->info("✅ {count($usuarios)} usuarios creados para {$tenant}");
    }
    
    private function getUsersForTenant($tenant)
    {
        $usuariosPorTenant = [
            'empresa1' => [
                ['nombre' => 'Juan Pérez - CEO Empresa1', 'email' => 'juan@empresa1.com', 'rol' => 'admin'],
                ['nombre' => 'María García - Manager Empresa1', 'email' => 'maria@empresa1.com', 'rol' => 'user'],
                ['nombre' => 'Carlos López - Dev Empresa1', 'email' => 'carlos@empresa1.com', 'rol' => 'user'],
                ['nombre' => 'Ana Martín - Designer Empresa1', 'email' => 'ana@empresa1.com', 'rol' => 'user'],
            ],
            'empresa2' => [
                ['nombre' => 'Pedro Rodríguez - Director Empresa2', 'email' => 'pedro@empresa2.com', 'rol' => 'admin'],
                ['nombre' => 'Laura Sánchez - Contadora Empresa2', 'email' => 'laura@empresa2.com', 'rol' => 'user'],
                ['nombre' => 'Miguel Torres - Vendedor Empresa2', 'email' => 'miguel@empresa2.com', 'rol' => 'user'],
                ['nombre' => 'Sofia Jiménez - Marketing Empresa2', 'email' => 'sofia@empresa2.com', 'rol' => 'user'],
            ],
            'empresa3' => [
                ['nombre' => 'Roberto Fernández - Fundador Empresa3', 'email' => 'roberto@empresa3.com', 'rol' => 'admin'],
                ['nombre' => 'Carmen Ruiz - RRHH Empresa3', 'email' => 'carmen@empresa3.com', 'rol' => 'user'],
                ['nombre' => 'Daniel Morales - Técnico Empresa3', 'email' => 'daniel@empresa3.com', 'rol' => 'user'],
                ['nombre' => 'Elena Vásquez - Secretaria Empresa3', 'email' => 'elena@empresa3.com', 'rol' => 'user'],
            ],
        ];
        
        return $usuariosPorTenant[$tenant] ?? [];
    }
}
