<?php

namespace App\Services;

use App\Models\Rol;
use App\Services\Response;
use Exception;

class UtilsService
{
    public function __construct()
    {
    }

    public function getRoles()
    {
        $response = new Response();
        try {
            $roles = Rol::where('estado', 'A')->get();
            $response->setData($roles);
        } catch (Exception $e) {
            $response->setMensaje($e->getMessage());
            $response->setOk(false);
            $response->setCode($e->getCode() !== 0 && $e->getCode() !== 1 && $e->getCode() >= 100 && $e->getCode() <= 599 ? $e->getCode() : 500);
        }

        return $response;
    }
}
