<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Model
{
    use HasApiTokens;
    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    protected $fillable = [
        'id_residente',
        'usuario',
        'contrasena',
        'estado',
        'fecha_creacion',
        'fecha_actualizacion'
    ];
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';
    public $timestamps = false;
}
