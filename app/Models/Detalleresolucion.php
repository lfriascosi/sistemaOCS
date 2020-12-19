<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalleresolucion extends Model
{
    protected $table = 'detalle_resoluciones';
    protected $fillable = ['iddetalleacta','descripcion'];
    public $timestamps = false;
}
