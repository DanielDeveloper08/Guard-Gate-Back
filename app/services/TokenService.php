<?php

namespace App\Services;

use App\Models\Token;
use Exception;
use Illuminate\Support\Facades\Log;

class TokenService
{
    public function obtenerTokens(): Response
    {
        $response = new Response();
        try {
            $tokens = Token::select('id_token', 'fecha_creacion', 'tiempo_expiracion')->where('estado', 'A')->get()->toArray();

            $response->setData($tokens);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $response->setMensaje($e->getMessage());
            $response->setOk(false);
            $response->setCode($e->getCode() !== 0 && $e->getCode() !== 1 && $e->getCode() >= 100 && $e->getCode() <= 599 ? $e->getCode() : 500);
        }

        return $response;
    }

    public function eliminarToken($token)
    {
        $response = new Response();
        try {
            $token = Token::findOrFail($token->id_token);
            $token->estado = 'I';
            $token->save();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $response->setMensaje($e->getMessage());
            $response->setOk(false);
            $response->setCode($e->getCode() !== 0 && $e->getCode() !== 1 && $e->getCode() >= 100 && $e->getCode() <= 599 ? $e->getCode() : 500);
        }

        return $response;
    }
}
