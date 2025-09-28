<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class SetTenantConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Solo cambiar la conexión si ya tenemos un tenant identificado
        $tenant = $request->attributes->get('tenant');
        
        if ($tenant) {
            // Configurar la conexión de BD para el tenant
            $this->configureTenantDatabase($tenant);
        }
        
        return $next($request);
    }
    
    /**
     * Configurar la conexión de base de datos para el tenant
     */
    private function configureTenantDatabase(string $tenant): void
    {
        $connectionName = "tenant_{$tenant}";
        
        // Verificar si la configuración del tenant existe
        if (Config::has("database.connections.{$connectionName}")) {
            // Cambiar la conexión por defecto a la del tenant
            Config::set('database.default', $connectionName);
            
            // Limpiar la cache de conexiones para forzar reconexión
            app('db')->purge($connectionName);
        }
    }
}
