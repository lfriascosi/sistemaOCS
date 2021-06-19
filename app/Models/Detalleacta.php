<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalleacta extends Model
{
    protected $table = 'detalle_actas';
    protected $fillable = [
        'idacta',
        'idordendia',
        'nombrealternativo',
        'descripcion'
    ];
    public $timestamps = false;

    public function acta(){
        return $this->belongsTo('App\Models\Acta');
    }
}
