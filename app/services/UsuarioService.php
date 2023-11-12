<?php

namespace App\Services;

use App\Models\Usuario;
use App\Services\Response;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Services\TokenOtpService;
use Illuminate\Support\Facades\DB;

class UsuarioService
{
    private $tokenOtpService;

    public function __construct()
    {
        $this->tokenOtpService = new TokenOtpService();
    }

    public function getCorreoUsuario($usuario): Response
    {
        $response = new Response();
        try {
            $user = Usuario::where('usuario', 'ILIKE',  $usuario)->first();

            if (!$user) {
                throw new Exception('Usuario no encontrado', 404);
            }

            $response->setData([
                'id_usuario' => $user->id_usuario,
                'correo' => $user->residente->correo,
                'nombres' => $user->residente->nombres,
                'apellidos' => $user->residente->apellidos,
            ]);
        } catch (Exception $e) {
            $response->setCode($e->getCode() !== 0 && $e->getCode() !== 1 && $e->getCode() >= 100 && $e->getCode() <= 599 ? $e->getCode() : 500);
            $response->setMensaje($e->getMessage());
            $response->setOk(false);
        }

        return $response;
    }

    public function reset_password($id_usuario, $contrasena)
    {
        DB::beginTransaction();
        $response = new Response();
        try {
            $user = Usuario::where('id_usuario', $id_usuario)->first();

            if (!$user) {
                throw new Exception('Usuario no encontrado', 404);
            }

            $user->contrasena = Hash::make($contrasena);
            $user->save();

            // eliminamos el otp del usuario
            $this->tokenOtpService->deleteTokenOtp($id_usuario);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("error " . $e->getMessage());
            $response->setCode($e->getCode() !== 0 && $e->getCode() !== 1 && $e->getCode() >= 100 && $e->getCode() <= 599 ? $e->getCode() : 500);
            $response->setMensaje($e->getMessage());
            $response->setOk(false);
        }

        return $response;
    }
}
