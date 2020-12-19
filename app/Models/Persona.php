<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    //protected $connection = 'mysqlocs';
    //protected $table = 'v_usuarios_SYS_OCS';
    protected $connection = 'mysql2';
    protected $table = 'v_usuarios_sys_ocs';
    protected $fillable = ['numeroIdentificacion','apellidos','nombres','EMail','perfil'];
}