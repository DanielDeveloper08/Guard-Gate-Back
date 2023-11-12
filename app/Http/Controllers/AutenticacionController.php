<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\SendCorreo;
use App\Services\AutenticacionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Services\TokenOtpService;
use App\Services\UsuarioService;
use Illuminate\Support\Facades\Log;

class AutenticacionController extends Controller
{
    private $autenticacionService;
    private $usuarioService;
    private $tokenOtpService;

    public function __construct()
    {
        $this->autenticacionService = new AutenticacionService();
        $this->usuarioService = new UsuarioService();
        $this->tokenOtpService = new TokenOtpService();
    }

    public function login(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->validate($request, [
                'usuario' => 'required|string|max:100',
                'contrasena' => 'required|string|max:100',
            ], [
                'usuario.required' => 'el usuario es requerido',
                'usuario.max' => 'el usuario debe tener máximo 100 caracteres',
                'contrasena.required' => 'la clave es requerida',
                'contrasena.max' => 'la clave debe tener máximo 100 caracteres',
            ]);

            $usuario = trim($request->input('usuario'));
            $contrasena = trim($request->input('contrasena'));

            $response = $this->autenticacionService->login($usuario, $contrasena);

            if (!$response->getOk()) {
                throw new Exception($response->getMensaje(), $response->getCode());
            }

            DB::commit();
            return response()->json([
                'message' => 'Inicio De Sesion Exitoso',
                'data' => $response->getData(),
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al crear usuario, ' . $e->getMessage(),
                'data' => []
            ], $e->getCode() ?: 400);
        }
    }

    public function register(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->validate($request, [
                'usuario' => 'required|string|unique:usuario|max:100',
                'contrasena' => 'required|string|confirmed|max:100', // tiene que pasarme otra vez la contraseña con el nombre contrasena_confirmation
                'nombres' => 'required|string|unique:residente|max:255',
                'apellidos' => 'required|string|unique:residente|max:255',
                'correo' => 'required|string|email|unique:residente|max:255',
                'telefono' => 'required|string|unique:residente|max:10',
            ], [
                'usuario.required' => 'el usuario es requerido',
                'usuario.unique' => 'el usuario ya existe',
                'usuario.max' => 'el usuario debe tener máximo 100 caracteres',
                'contrasena.required' => 'la clave es requerida',
                'contrasena.confirmed' => 'la clave no coincide',
                'contrasena.max' => 'la clave debe tener máximo 100 caracteres',
                'nombres.required' => 'el nombre es requerido',
                'nombres.unique' => 'el nombre ya existe',
                'nombres.max' => 'el nombre debe tener máximo 255 caracteres',
                'apellidos.required' => 'el apellido es requerido',
                'apellidos.unique' => 'el apellido ya existe',
                'apellidos.max' => 'el apellido debe tener máximo 255 caracteres',
                'correo.required' => 'el correo es requerido',
                'correo.email' => 'el correo no es válido',
                'correo.unique' => 'el correo ya existe',
                'correo.max' => 'el correo debe tener máximo 255 caracteres',
                'telefono.required' => 'el telefono es requerido',
                'telefono.unique' => 'el telefono ya existe',
                'telefono.max' => 'el telefono debe tener máximo 10 caracteres',
            ]);

            $usuario = trim($request->input('usuario'));
            $contrasena = Hash::make(trim($request->input('contrasena')));
            $nombres = trim(strtoupper($request->input('nombres')));
            $apellidos = trim(strtoupper($request->input('apellidos')));
            $correo = trim($request->input('correo'));
            $telefono = trim($request->input('telefono'));

            $informacion = (object)[
                'usuario' => $usuario,
                'contrasena' => $contrasena,
                'nombres' => $nombres,
                'apellidos' => $apellidos,
                'correo' => $correo,
                'telefono' => $telefono,
            ];

            $response = $this->autenticacionService->register($informacion);

            if (!$response->getOk()) {
                throw new Exception($response->getMensaje(), $response->getCode());
            }

            DB::commit();
            return response()->json([
                'message' => 'Usuario creado exitosamente',
                'data' => $response->getData(),
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al crear usuario, ' . $e->getMessage(),
                'data' => [],
            ], $e->getCode() ?: 400);
        }
    }

    public function sendEmailResetPassword(Request $request)
    {
        try {
            $usuario = trim($request->input('usuario'));

            $findUser = $this->usuarioService->getCorreoUsuario($usuario);

            if (!$findUser->getOk()) {
                throw new Exception($findUser->getMensaje(), $findUser->getCode());
            }

            $informacion = (object)$findUser->getData();

            // enviamos correo con el codigo
            $codigo = random_int(100000, 999999);

            Mail::to($informacion->correo)->send(new SendCorreo($informacion->nombres, $informacion->apellidos, $codigo));

            // guardamos la informacion en la tabla token_otp
            $response = $this->tokenOtpService->store($informacion->id_usuario, $codigo);

            if (!$response->getOk()) {
                throw new Exception($response->getMensaje(), $response->getCode());
            }

            return response()->json([
                'message' => 'Correo enviado exitosamente',
                'data' => [
                    'id_usuario' => $informacion->id_usuario,
                    'correo' => $informacion->correo,
                    'nombres' => $informacion->nombres,
                    'apellidos' => $informacion->apellidos,
                ],
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al enviar correo para recuperar contraseña, ' . $e->getMessage(),
                'data' => [],
            ], $e->getCode() ?: 400);
        }
    }

    public function validateOtp(Request $request)
    {
        try {
            $codigo = trim($request->input('codigo'));
            $id_usuario = trim($request->input('id_usuario'));

            $tieneToken = $this->tokenOtpService->findToken($id_usuario);

            if (!$tieneToken->getOk()) {
                throw new Exception($tieneToken->getMensaje(), $tieneToken->getCode());
            }

            $token = (object)$tieneToken->getData();

            if ($token->codigo !== $codigo) {
                throw new Exception('El codigo ingresado no es correcto', 400);
            }

            return response()->json([
                'message' => 'Codigo correcto',
                'data' => [
                    'id_usuario' => $id_usuario,
                    'url' => '/autenticacion/cambiarContrasena'
                ],
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al validar codigo, ' . $e->getMessage(),
                'data' => [],
            ], $e->getCode() ?: 400);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $nueva_contrasena = trim($request->input('nueva_contrasena'));
            $id_usuario = trim($request->input('id_usuario'));

            $response = $this->usuarioService->reset_password($id_usuario, $nueva_contrasena);

            if (!$response->getOk()) {
                throw new Exception($response->getMensaje(), $response->getCode());
            }

            return response()->json([
                'message' => 'Contraseña actualizada exitosamente',
                'data' => [],
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al resetear contraseña, ' . $e->getMessage(),
                'data' => [],
            ], $e->getCode() ?: 400);
        }
    }
}
