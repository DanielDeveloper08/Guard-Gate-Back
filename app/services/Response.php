<?php

namespace App\Services;

class Response
{
    private $ok;
    private $mensaje;
    private $data;
    private $code;

    public function __construct($ok = true, $mensaje = "", $data = [], $code = 200)
    {
        $this->ok = $ok;
        $this->mensaje = $mensaje;
        $this->data = $data;
        $this->code = $code;
    }

    public function getOk()
    {
        return $this->ok;
    }

    public function setOk($ok)
    {
        $this->ok = $ok;
    }

    public function getMensaje()
    {
        return $this->mensaje;
    }

    public function setMensaje($mensaje)
    {
        $this->mensaje = $mensaje;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data = [])
    {
        $this->data = $data;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code = 200)
    {
        $this->code = $code;
    }
}
