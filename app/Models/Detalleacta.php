<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalleacta extends Model
{
    protected $table = 'detalle_actas';
    protected $fillable = ['idacta','idordendia','descripcion'];
    public $timestamps = false;
}
