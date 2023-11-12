<?php

namespace App\Services;

use App\Models\Token;
use App\Models\Usuario;
use App\Models\UsuarioDetalle;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AutenticacionService
{
    public function login($usuario, $contrasena): Response
    {
        $response = new Response();
        try {
            $user = Usuario::where('usuario', $usuario)->where('estado', 'A')->first();
            if (!$user) {
                throw new Exception('Usuario no encontrado', 404);
            }
            if (!Hash::check($contrasena, $user->contrasena)) {
                throw new Exception('contrasena incorrecta', 400);
            }

            $info_token = (object)[
                'token' => $user->createToken('authToken')->plainTextToken,
                'expires_at' => getExpiredToken(),
            ];

            // guardamos el token en la tabla token
            $token = Token::create([
                'id_usuario' => $user->id_usuario,
                'token' => $info_token->token,
                'tiempo_expiracion' => $info_token->expires_at,
            ]);

            if (!$token) {
                throw new Exception('Error al crear el token', 500);
            }

            $response->setData([
                'usuario' => [
                    'id_usuario' => $user->id_usuario,
                    'usuario' => $user->usuario,
                ],
                'informacion_token' => $info_token,
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $response->setMensaje($e->getMessage());
            $response->setOk(false);
            $response->setCode($e->getCode() !== 0 && $e->getCode() !== 1 && $e->getCode() >= 100 && $e->getCode() <= 599 ? $e->getCode() : 500);
        }

        return $response;
    }

    public function register($informacion): Response
    {
        $response = new Response();
        try {
            $usuario_detalle = UsuarioDetalle::create([
                'nombres' => $informacion->nombres,
                'apellidos' => $informacion->apellidos,
                'correo' => $informacion->correo,
                'telefono' => $informacion->telefono,
                'fecha_creacion' => date('Y-m-d H:i:s'),
            ]);

            if (!$usuario_detalle) {
                throw new Exception('Error al crear el usuario', 500);
            }

            // creamos al usuario
            $usuario = Usuario::create([
                'id_usuario_detalle' => $usuario_detalle->id_usuario_detalle,
                'id_rol' => $informacion->rol,
                'usuario' => $informacion->usuario,
                'contrasena' => $informacion->contrasena,
                'estado' => 'A',
                'fecha_creacion' => date('Y-m-d H:i:s'),
            ]);

            if (!$usuario) {
                throw new Exception('Error al crear el usuario', 500);
            }

            $response->setData([
                'informacion_usuario' => [
                    'usuario' => $usuario->usuario,
                    'nombres' => $usuario_detalle->nombres,
                    'apellidos' => $usuario_detalle->apellidos,
                    'correo' => $usuario_detalle->correo,
                    'telefono' => $usuario_detalle->telefono,
                ],
            ]);
        } catch (Exception $e) {
            $response->setMensaje($e->getMessage());
            $response->setOk(false);
            $response->setCode($e->getCode() !== 0 && $e->getCode() !== 1 && $e->getCode() >= 100 && $e->getCode() <= 599 ? $e->getCode() : 500);
        }

        return $response;
    }
}
