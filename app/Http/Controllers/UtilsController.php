<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\UtilsService;
use Exception;
use Illuminate\Http\Request;

class UtilsController extends Controller
{
    protected $utilsService;

    public function __construct()
    {
        $this->utilsService = new UtilsService();
    }
    public function obtenerRoles()
    {
        try {
            $roles = $this->utilsService->getRoles();

            if (!$roles->getOk()) {
                throw new Exception($roles->getMensaje(), $roles->getCode());
            }

            return response()->json([
                'message' => 'Roles obtenidos correctamente',
                'data' => $roles->getData(),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => "Error al obtener los roles " . $e->getMessage(),
                'data' => [],
            ], $e->getCode());
        }
    }
}
