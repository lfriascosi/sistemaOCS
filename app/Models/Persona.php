<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'vista_personas';
    protected $fillable = ['nombre','tipo_documento','num_documento','direccion','telefono','email'];
}