<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCorreo extends Mailable
{
    use Queueable, SerializesModels;

    private $nombres;
    private $apellidos;
    private $codigo;

    public function __construct($nombres, $apellidos, $codigo)
    {
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->codigo = $codigo;
    }

    public function build()
    {
        return $this->view('codigo_correo')
            ->from("dalemberismael7@gmail.com", "Guard Gate")
            ->subject('Código de verificación')
            ->with([
                'nombres' => $this->nombres,
                'apellidos' => $this->apellidos,
                'codigo' => $this->codigo,
            ]);
    }
}
