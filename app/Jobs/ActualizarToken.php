<?php

namespace App\Jobs;

use App\Services\TokenService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ActualizarToken implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tokenservice;

    public function __construct()
    {
        $this->tokenservice = new TokenService();
    }

    public function isExpired($token): bool
    {
        $fechaCreacion = strtotime($token['fecha_creacion']);
        $tiempoExpiracion = $token['tiempo_expiracion'];
        $fechaActual = time();
        $fechaExpiracion = $fechaCreacion + $tiempoExpiracion;

        return $fechaActual > $fechaExpiracion;
    }

    public function handle(): void
    {
        $tokens = $this->tokenservice->obtenerTokens()->getData();
        foreach ($tokens as $token) {
            if ($this->isExpired($token)) {
                if ($this->tokenservice->eliminarToken((object)$token)->getOk()) {
                    Log::info('Token eliminado: ' . $token['id_token']);
                }
            }
        }
    }
}
