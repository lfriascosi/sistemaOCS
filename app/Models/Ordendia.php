<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordendia extends Model
{
    protected $table = 'orden_dias';
    protected $fillable = ['idconvocatoria','numconvocatoria','nombre','condicion'];

    public $timestamps = false;
    
    public function convocatoria(){
        return $this->belongsTo('App\Convocatoria');
    }
}
