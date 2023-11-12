<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'rol';
    protected $primaryKey = 'id_rol';
    protected $fillable = [
        'id_rol',
        'nombre',
        'estado',
        'fecha_creacion',
        'fecha_actualizacion'
    ];
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';
    public $timestamps = false;
}
