<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordendia extends Model
{
    protected $table = 'orden_dias';
    protected $fillable = ['idconvocatoria','nombre','condicion'];

    public function convocatorias(){
        return $this->hasMany('App\Convocatoria');
    }
}
