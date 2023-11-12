<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class TokenOtp extends Model
{
    use HasApiTokens;
    protected $table = 'token_otp';
    protected $primaryKey = 'id_token_otp';
    protected $fillable = [
        'id_token_otp',
        'id_usuario',
        'codigo',
        'estado',
        'fecha_creacion',
        'fecha_actualizacion'
    ];
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';
    public $timestamps = false;
}
