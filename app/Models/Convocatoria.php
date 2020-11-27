<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convocatoria extends Model
{
    protected $fillable = [
        'iduser',
        'titulo',
        'codigo',
        'descripcion',
        'estado'
    ];
}
