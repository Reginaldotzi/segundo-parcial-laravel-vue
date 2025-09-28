<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $tenant = $this->getTenantFromHost($host) ?? $this->getTenantFromHeader($request);
        
        if ($tenant) {
            // Agregar información del tenant al request ANTES de la autenticación
            $request->attributes->set('tenant', $tenant);
        }
        
        return $next($request);
    }
    
    /**
     * Extraer el tenant del host
     */
    private function getTenantFromHost(string $host): ?string
    {
        // Detectar subdominios como empresa1.localhost, empresa2.localhost
        if (preg_match('/^(empresa\d+)\./', $host, $matches)) {
            return $matches[1];
        }
        
        return null;
    }
    
    /**
     * Extraer el tenant del header (para desarrollo local)
     */
    private function getTenantFromHeader(Request $request): ?string
    {
        $tenant = $request->header('X-Tenant');
        
        // Validar que el tenant existe
        if ($tenant && in_array($tenant, ['empresa1', 'empresa2', 'empresa3'])) {
            return $tenant;
        }
        
        return null;
    }
    
    /**
     * Configurar la base de datos del tenant
     */
    private function configureTenantDatabase(string $tenant): void
    {
        $connectionName = "tenant_{$tenant}";
        
        // Verificar si la conexión existe
        $connections = Config::get('database.connections');
        if (!isset($connections[$connectionName])) {
            // Si no existe, usar empresa1 como fallback
            $connectionName = 'tenant_empresa1';
        }
        
        // Establecer la conexión por defecto para este request
        Config::set('database.default', $connectionName);
        
        // Limpiar conexiones existentes para forzar nueva conexión
        DB::purge($connectionName);
    }
}