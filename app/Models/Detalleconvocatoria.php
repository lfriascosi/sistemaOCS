<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detalleconvocatoria extends Model
{
        protected $table = 'detalle_convocatorias';
        protected $fillable = ['iduser','idconvocatoria'];
        public $timestamps = false;
}
