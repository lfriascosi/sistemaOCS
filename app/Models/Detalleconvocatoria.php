<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalleconvocatoria extends Model
{
    protected $table = 'detalle_convocatorias';
    protected $fillable = ['iduser','idconvocatoria'];

    public function convocatorias(){
        return $this->hasMany('App\Convocatoria');
    }
}
