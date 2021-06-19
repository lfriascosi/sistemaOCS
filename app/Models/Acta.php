<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acta extends Model
{
    protected $fillable = [
        'iduser',
        'titulo',
        'codigo',
        'descripcion',
        'estado',
        'condicion'
    ];
    public function detalleactas(){
        return $this->hasMany('App\Models\Detalleacta');
    }
}
