<?php

namespace App\Services;

use App\Models\TokenOtp;
use App\Models\Usuario;
use App\Services\Response;
use Exception;

class TokenOtpService
{
    public function store($id_usuario, $codigo)
    {
        $response = new Response();
        try {
            // buscamos si existe el usuario y esta activo
            $usuario = Usuario::where('id_usuario', $id_usuario)->where('estado', 'A')->first();

            if (!$usuario) {
                throw new Exception('Usuario no encontrado', 404);
            }

            // buscamos si existe un token activo
            $token = TokenOtp::where('id_usuario', $id_usuario)->where('estado', 'A')->first();

            if ($token) {
                $token->estado = 'I';
                $token->fecha_actualizacion = getFechaActual();
                $token->save();
            }

            // guardamos el token
            $token = new TokenOtp();
            $token->id_usuario = $id_usuario;
            $token->codigo = $codigo;
            $token->save();
        } catch (Exception $e) {
            $response->setCode($e->getCode() !== 0 && $e->getCode() !== 1 && $e->getCode() >= 100 && $e->getCode() <= 599 ? $e->getCode() : 500);
            $response->setMensaje($e->getMessage());
            $response->setOk(false);
        }

        return $response;
    }

    public function findToken($id_usuario)
    {
        $response = new Response();
        try {
            $token = TokenOtp::where('id_usuario', $id_usuario)->where('estado', 'A')->first();

            if (!$token) {
                throw new Exception('Token no encontrado', 404);
            }

            $response->setData($token);
        } catch (Exception $e) {
            $response->setCode($e->getCode() !== 0 && $e->getCode() !== 1 && $e->getCode() >= 100 && $e->getCode() <= 599 ? $e->getCode() : 500);
            $response->setMensaje($e->getMessage());
            $response->setOk(false);
        }

        return $response;
    }

    public function deleteTokenOtp($id_usuario)
    {
        $response = new Response();
        try {
            $token = TokenOtp::where('id_usuario', $id_usuario)->where('estado', 'A')->first();

            if (!$token) {
                throw new Exception('Token no encontrado', 404);
            }

            $token->estado = 'I';
            $token->save();
        } catch (Exception $e) {
            $response->setCode($e->getCode() !== 0 && $e->getCode() !== 1 && $e->getCode() >= 100 && $e->getCode() <= 599 ? $e->getCode() : 500);
            $response->setMensaje($e->getMessage());
            $response->setOk(false);
        }

        return $response;
    }
}
