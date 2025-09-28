<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class MigrateTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:migrate {tenant}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrar base de datos para un tenant específico';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenant = $this->argument('tenant');
        $connection = "tenant_{$tenant}";
        
        // Verificar si la conexión existe
        $connections = Config::get('database.connections');
        if (!isset($connections[$connection])) {
            $this->error("La conexión {$connection} no existe en la configuración.");
            return 1;
        }
        
        // Cambiar temporalmente la conexión por defecto
        $originalConnection = Config::get('database.default');
        Config::set('database.default', $connection);
        
        $this->info("Migrando base de datos para tenant: {$tenant}");
        
        try {
            // Ejecutar migraciones
            Artisan::call('migrate', ['--force' => true]);
            $this->info("✅ Migraciones ejecutadas para {$tenant}");
            
            // Ejecutar seeders
            Artisan::call('db:seed', ['--class' => 'UsuarioSeeder', '--force' => true]);
            $this->info("✅ Seeders ejecutados para {$tenant}");
            
        } catch (\Exception $e) {
            $this->error("Error al migrar {$tenant}: " . $e->getMessage());
            return 1;
        } finally {
            // Restaurar conexión original
            Config::set('database.default', $originalConnection);
        }
        
        $this->info("🎉 Tenant {$tenant} configurado correctamente!");
        return 0;
    }
}
