<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class UsuarioDetalle extends Model
{
    use HasApiTokens;
    protected $table = 'usuario_detalle';
    protected $primaryKey = 'id_usuario_detalle';
    protected $fillable = [
        'id_usuario_detalle',
        'nombres',
        'apellidos',
        'correo',
        'telefono',
        'fecha_creacion',
        'fecha_actualizacion'
    ];
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';
    public $timestamps = false;
}
