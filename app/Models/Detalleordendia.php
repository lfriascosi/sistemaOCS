<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalleordendia extends Model
{
    protected $table = 'detalle_orden_dias';
    protected $fillable = ['iduser','idordendia'];
    public $timestamps = false;
}
