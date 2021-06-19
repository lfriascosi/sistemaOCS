<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resolucion extends Model
{
    protected $table = 'resoluciones';
    protected $fillable = [
        'idordendia',
        'idacta',
        'codigo',
        'nombrealternativo',
        'descripcion'
    ];
}
