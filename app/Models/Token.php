<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Token extends Model
{
    use HasApiTokens;
    protected $table = 'token';
    protected $primaryKey = 'id_token';
    protected $fillable = [
        'id_usuario',
        'token',
        'estado',
        'tiempo_expiracion',
        'fecha_creacion',
        'fecha_actualizacion'
    ];
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';
    public $timestamps = false;
}
